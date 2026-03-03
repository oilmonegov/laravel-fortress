<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P07;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class TodoFixmeCheck extends BaseCheck
{
    public function id(): string
    {
        return 'todo_fixme';
    }

    public function ruleId(): string
    {
        return 'F-P07-020';
    }

    public function part(): string
    {
        return 'P07';
    }

    public function severity(): string
    {
        return 'info';
    }

    public function description(): string
    {
        return 'Detect TODO, FIXME, HACK, and XXX comments';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            $matches = $this->matchPattern($content, '/(?:\/\/|#|\*)\s*(?:TODO|FIXME|HACK|XXX)\b/i');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Code marker comment found that indicates incomplete or problematic code.',
                    'Create a ticket/issue to track this work, then resolve or remove the comment.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
