<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P14;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class DebugModeCheck extends BaseCheck
{
    public function id(): string
    {
        return 'debug_mode';
    }

    public function ruleId(): string
    {
        return 'F-P14-001';
    }

    public function part(): string
    {
        return 'P14';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect hardcoded debug mode enabled in configuration';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->configFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Hardcoded 'debug' => true (not env())
            $matches = $this->matchPattern($content, '/["\']debug["\']\s*=>\s*true/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                // Skip if it uses env()
                if (str_contains($line, 'env(')) {
                    continue;
                }

                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Debug mode is hardcoded to `true` in config. This exposes stack traces and sensitive data in production.',
                    'Use `\'debug\' => env(\'APP_DEBUG\', false)` to control debug mode via environment.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        // Check for .env.production or .env with APP_DEBUG=true
        $envProd = $context->basePath().'/.env.production';
        if (file_exists($envProd)) {
            $envContent = file_get_contents($envProd) ?: '';
            if (preg_match('/^APP_DEBUG\s*=\s*true/mi', $envContent)) {
                $results[] = $this->result(
                    '.env.production',
                    null,
                    '`APP_DEBUG=true` in production environment file exposes sensitive information.',
                    'Set `APP_DEBUG=false` in `.env.production`.',
                );
            }
        }

        return $results;
    }
}
