<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P02;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class WeakHashingCheck extends BaseCheck
{
    public function id(): string
    {
        return 'weak_hashing';
    }

    public function ruleId(): string
    {
        return 'F-P02-001';
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
        return 'Detect weak hashing algorithms used for security purposes';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // md5() for security (exclude non-security uses like cache keys by checking context)
            $matches = $this->matchPattern($content, '/\bmd5\s*\(/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                // Skip cache key / etag usage
                if (preg_match('/(?:cache|etag|checksum|fingerprint|hash_file)/i', $line)) {
                    continue;
                }
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`md5()` is cryptographically broken and should not be used for security.',
                    'Use `Hash::make()` for passwords, `hash(\'sha256\', $data)` for integrity, or `password_hash()` for authentication.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // sha1() for security
            $matches = $this->matchPattern($content, '/\bsha1\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    '`sha1()` is considered weak for security purposes.',
                    'Use `hash(\'sha256\', $data)` or `Hash::make()` for passwords.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
