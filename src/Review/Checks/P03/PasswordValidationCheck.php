<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P03;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class PasswordValidationCheck extends BaseCheck
{
    public function id(): string
    {
        return 'password_validation';
    }

    public function ruleId(): string
    {
        return 'F-P03-015';
    }

    public function part(): string
    {
        return 'P03';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect weak password validation rules';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Simple 'min:X' password rule with low minimum
            if (preg_match('/["\']password["\']\s*=>\s*.*["\']min:([1-7])["\']/', $content, $match)) {
                $lineNum = substr_count(substr($content, 0, (int) strpos($content, $match[0])), "\n") + 1;
                $results[] = $this->result(
                    $relativePath,
                    $lineNum,
                    "Password minimum length is only {$match[1]} characters, which is too weak.",
                    'Use `Password::min(8)->mixedCase()->numbers()->uncompromised()` for strong password validation.',
                    $this->getSnippet($content, $lineNum),
                );
            }

            // Password field with just 'required' and no Password rule
            $matches = $this->matchPattern($content, '/["\']password["\']\s*=>\s*[\["\']required["\']/');
            foreach ($matches as $match) {
                $line = explode("\n", $content)[$match['line'] - 1] ?? '';
                if (! str_contains($line, 'Password::') && ! str_contains($line, 'password_rule')) {
                    $results[] = $this->result(
                        $relativePath,
                        $match['line'],
                        'Password validation uses only basic rules without complexity requirements.',
                        'Use `Password::min(8)->mixedCase()->numbers()->uncompromised()` for proper password strength validation.',
                        $this->getSnippet($content, $match['line']),
                    );
                }
            }
        }

        return $results;
    }
}
