<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P13;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class SensitiveDataLogCheck extends BaseCheck
{
    public function id(): string
    {
        return 'sensitive_data_log';
    }

    public function ruleId(): string
    {
        return 'F-P13-001';
    }

    public function part(): string
    {
        return 'P13';
    }

    public function severity(): string
    {
        return 'critical';
    }

    public function description(): string
    {
        return 'Detect logging of sensitive data (passwords, tokens, credit cards)';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Log:: calls with sensitive variable names
            $matches = $this->matchPattern($content, '/Log::(?:info|debug|warning|error|critical)\s*\([^)]*\$(?:password|token|secret|credit_card|card_number|cvv|ssn|api_key)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Sensitive data may be written to log files.',
                    'Mask sensitive fields before logging: `Str::mask($token, \'*\', 4)`. Never log passwords, tokens, or financial data.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // logger() calls with sensitive data
            $matches = $this->matchPattern($content, '/logger\s*\(\s*\)->(?:info|debug|warning|error)\s*\([^)]*\$(?:password|token|secret|credit_card)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Sensitive data may be written to log files via `logger()`.',
                    'Redact sensitive values before logging. Use structured logging with explicitly safe fields.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // Logging $request with potential sensitive fields
            $matches = $this->matchPattern($content, '/Log::(?:info|debug)\s*\([^)]*\$request->all\s*\(\)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Logging entire request payload may include passwords, tokens, or other sensitive data.',
                    'Log only specific safe fields: `Log::info(\'...\', $request->only([\'name\', \'email\']))`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
