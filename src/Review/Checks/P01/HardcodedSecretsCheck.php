<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class HardcodedSecretsCheck extends BaseCheck
{
    public function id(): string
    {
        return 'hardcoded_secrets';
    }

    public function ruleId(): string
    {
        return 'F-P01-015';
    }

    public function part(): string
    {
        return 'P01';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect hardcoded API keys, passwords, and tokens in source code';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];
        $patterns = [
            '/(?:api_key|apikey|api_secret)\s*[=:]\s*["\'][a-zA-Z0-9_\-]{16,}["\']/' => 'API key',
            '/(?:password|passwd|pwd)\s*[=:]\s*["\'][^"\']{8,}["\']/' => 'password',
            '/(?:secret|token|auth_token|access_token)\s*[=:]\s*["\'][a-zA-Z0-9_\-]{16,}["\']/' => 'secret/token',
            '/(?:sk_live|pk_live|sk_test|pk_test)_[a-zA-Z0-9]{20,}/' => 'Stripe key',
            '/(?:AKIA|ASIA)[A-Z0-9]{16}/' => 'AWS access key',
            '/Bearer\s+[a-zA-Z0-9_\-\.]{20,}/' => 'Bearer token',
        ];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Skip config files (they're expected to reference env())
            if (str_starts_with($relativePath, 'config/')) {
                continue;
            }

            foreach ($patterns as $pattern => $type) {
                $matches = $this->matchPattern($content, $pattern);
                foreach ($matches as $match) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        "Possible hardcoded {$type} found in source code.",
                        'Move to `.env` file and reference via `config()` helper. Never commit secrets to version control.',
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }
        }

        return $results;
    }
}
