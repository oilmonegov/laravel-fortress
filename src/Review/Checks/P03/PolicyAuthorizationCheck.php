<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P03;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class PolicyAuthorizationCheck extends BaseCheck
{
    public function id(): string
    {
        return 'policy_authorization';
    }

    public function ruleId(): string
    {
        return 'F-P03-020';
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
        return 'Detect controllers with authorization calls but no matching policy';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        // Find controllers that use authorize() calls
        foreach ($context->controllerFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // Controllers that have model operations but no authorize/can/policy check
            if (preg_match('/(?:::create|::update|->delete|->destroy)\s*\(/', $content)
                && ! preg_match('/(?:\$this->authorize|Gate::|->can\(|->cannot\(|->authorize\()/', $content)) {
                $results[] = $this->result(
                    $relativePath,
                    null,
                    'Controller performs write operations without explicit authorization checks.',
                    'Add `$this->authorize(\'action\', $model)` calls or use `->can()` middleware on routes. Create a policy if one does not exist.',
                );
            }
        }

        return $results;
    }
}
