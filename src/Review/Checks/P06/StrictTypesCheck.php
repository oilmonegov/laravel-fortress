<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P06;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class StrictTypesCheck extends BaseCheck
{
    public function id(): string
    {
        return 'strict_types';
    }

    public function ruleId(): string
    {
        return 'F-P06-001';
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
        return 'Detect PHP files missing declare(strict_types=1)';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            if (! str_starts_with(trim($content), '<?php')) {
                continue;
            }

            if (! str_contains($content, 'declare(strict_types=1)')) {
                $results[] = $this->result(
                    $relativePath,
                    1,
                    'PHP file is missing `declare(strict_types=1)` declaration.',
                    'Add `declare(strict_types=1);` immediately after the `<?php` opening tag.',
                );
            }
        }

        return $results;
    }
}
