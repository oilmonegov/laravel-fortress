<?php

declare(strict_types=1);

namespace Fortress\Review;

interface FortressCheck
{
    public function id(): string;

    public function ruleId(): string;

    public function part(): string;

    public function severity(): string;

    public function description(): string;

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array;
}
