<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P02;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class HardcodedKeysCheck extends BaseCheck
{
    public function id(): string
    {
        return 'hardcoded_keys';
    }

    public function ruleId(): string
    {
        return 'F-P02-003';
    }

    public function part(): string
    {
        return 'P02';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect hardcoded encryption keys or IVs in source code';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (str_starts_with($relativePath, 'config/')) {
                continue;
            }

            // Crypt/openssl with hardcoded key
            $matches = $this->matchPattern($content, '/(?:openssl_encrypt|openssl_decrypt|Crypt::encrypt)\s*\([^)]*["\'][a-zA-Z0-9+\/=]{16,}["\']/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Encryption operation uses a hardcoded key or IV.',
                    'Use `config(\'app.key\')` for the application encryption key. Rotate keys with `php artisan key:generate`.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // Direct base64 key assignment
            $matches = $this->matchPattern($content, '/(?:encryption_key|cipher_key|secret_key)\s*=\s*["\']base64:[a-zA-Z0-9+\/=]+["\']/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Encryption key is hardcoded in source code.',
                    'Store encryption keys in `.env` and access via `config()`. Never commit keys to version control.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
