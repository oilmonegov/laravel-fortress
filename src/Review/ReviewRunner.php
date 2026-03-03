<?php

declare(strict_types=1);

namespace Fortress\Review;

use Symfony\Component\Finder\Finder;

class ReviewRunner
{
    /** @var FortressCheck[] */
    private array $checks = [];

    /** @var string[] */
    private array $enabledParts = [];

    private string $minimumSeverity = 'info';

    /** @var array<string, int> */
    private const SEVERITY_ORDER = [
        'critical' => 3,
        'warning' => 2,
        'info' => 1,
    ];

    public function __construct(
        private readonly string $basePath,
    ) {
        $this->discoverChecks();
    }

    /** @param string[] $parts */
    public function filterByParts(array $parts): self
    {
        $this->enabledParts = array_map('strtoupper', $parts);

        return $this;
    }

    public function filterBySeverity(string $severity): self
    {
        $this->minimumSeverity = strtolower($severity);

        return $this;
    }

    /**
     * @param  callable(FortressCheck, int, int): void|null  $onProgress
     * @return CheckResult[]
     */
    public function run(?callable $onProgress = null): array
    {
        $checks = $this->getFilteredChecks();
        $results = [];
        $total = count($checks);

        foreach ($checks as $index => $check) {
            if ($onProgress !== null) {
                $onProgress($check, $index + 1, $total);
            }

            $context = new ReviewContext($this->basePath);
            $checkResults = $check->run($context);

            // Filter by severity threshold
            foreach ($checkResults as $result) {
                if ($this->meetsMinimumSeverity($result->severity)) {
                    $results[] = $result;
                }
            }
        }

        return $results;
    }

    /** @return FortressCheck[] */
    public function getFilteredChecks(): array
    {
        if (empty($this->enabledParts)) {
            return $this->checks;
        }

        return array_values(array_filter(
            $this->checks,
            fn (FortressCheck $check) => in_array(strtoupper($check->part()), $this->enabledParts, true),
        ));
    }

    /** @return array<string, string> */
    public function getAvailableParts(): array
    {
        $parts = [];
        foreach ($this->checks as $check) {
            $part = strtoupper($check->part());
            if (! isset($parts[$part])) {
                $parts[$part] = $this->partName($part);
            }
        }
        ksort($parts);

        return $parts;
    }

    public function totalChecks(): int
    {
        return count($this->getFilteredChecks());
    }

    private function discoverChecks(): void
    {
        $checksDir = dirname(__DIR__).'/Review/Checks';

        if (! is_dir($checksDir)) {
            return;
        }

        $finder = (new Finder)->files()->name('*Check.php')->in($checksDir)->sortByName();

        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();
            $className = str_replace(['/', '.php'], ['\\', ''], $relativePath);
            $fqcn = 'Fortress\\Review\\Checks\\'.$className;

            if (class_exists($fqcn)) {
                $instance = new $fqcn;
                if ($instance instanceof FortressCheck) {
                    $this->checks[] = $instance;
                }
            }
        }
    }

    private function meetsMinimumSeverity(string $severity): bool
    {
        $min = self::SEVERITY_ORDER[$this->minimumSeverity] ?? 1;
        $actual = self::SEVERITY_ORDER[strtolower($severity)] ?? 1;

        return $actual >= $min;
    }

    private function partName(string $part): string
    {
        return match ($part) {
            'P01' => 'Application Security',
            'P02' => 'Cryptography',
            'P03' => 'Authentication & Authorization',
            'P04' => 'Data Integrity',
            'P05' => 'Financial Accuracy',
            'P06' => 'PHP Language',
            'P07' => 'Clean Code',
            'P08' => 'Laravel Framework',
            'P09' => 'Database',
            'P10' => 'Frontend',
            'P11' => 'Testing',
            'P12' => 'APIs & Queues',
            'P13' => 'Logging',
            'P14' => 'Infrastructure',
            default => $part,
        };
    }
}
