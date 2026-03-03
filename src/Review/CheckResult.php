<?php

declare(strict_types=1);

namespace Fortress\Review;

readonly class CheckResult
{
    public function __construct(
        public string $ruleId,
        public string $severity,
        public string $file,
        public ?int $line,
        public string $problem,
        public string $solution,
        public ?string $snippet = null,
    ) {}
}
