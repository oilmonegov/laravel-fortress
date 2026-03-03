<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P09;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class MigrationSafetyCheck extends BaseCheck
{
    public function id(): string
    {
        return 'migration_safety';
    }

    public function ruleId(): string
    {
        return 'F-P09-015';
    }

    public function part(): string
    {
        return 'P09';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect potentially destructive migration operations';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->migrationFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // dropColumn
            $matches = $this->matchPattern($content, '/->dropColumn\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Migration drops a column which will permanently delete data.',
                    'Guard with `Schema::hasColumn()` check. Ensure data has been migrated or backed up before dropping.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // dropTable / drop()
            $matches = $this->matchPattern($content, '/Schema::drop(?:IfExists)?\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Migration drops an entire table which will permanently delete all data.',
                    'Ensure all data has been backed up or migrated. Consider using soft-delete or archival instead.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // renameColumn (can fail on certain DB engines)
            $matches = $this->matchPattern($content, '/->renameColumn\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Column rename may cause issues with existing code referencing the old name.',
                    'Consider a create-copy-drop migration pattern for safer column renames, and update all code references.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
