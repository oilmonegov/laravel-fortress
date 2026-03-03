<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P06;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class TypeDeclarationCheck extends BaseCheck
{
    public function id(): string
    {
        return 'type_declaration';
    }

    public function ruleId(): string
    {
        return 'F-P06-005';
    }

    public function part(): string
    {
        return 'P06';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect methods missing return type declarations or untyped parameters';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Skip migrations and config files
            if (preg_match('/(?:migrations|config)[\/\\\\]/', $relativePath)) {
                continue;
            }

            // Public/protected methods without return type
            $matches = $this->matchPattern($content, '/(?:public|protected|private)\s+function\s+\w+\s*\([^)]*\)\s*\{/');
            foreach ($matches as $match) {
                // Skip constructors, destructors, and magic methods
                if (preg_match('/__(?:construct|destruct|toString|invoke)\s*\(/', $match['match'])) {
                    continue;
                }

                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Method is missing a return type declaration.',
                    'Add an explicit return type: `public function methodName(): ReturnType`.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
