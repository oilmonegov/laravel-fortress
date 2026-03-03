<?php

declare(strict_types=1);

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

// Create a concrete test implementation
beforeEach(function () {
    $this->check = new class extends BaseCheck
    {
        public function id(): string
        {
            return 'test_check';
        }

        public function ruleId(): string
        {
            return 'F-TEST-001';
        }

        public function part(): string
        {
            return 'P99';
        }

        public function severity(): string
        {
            return 'warning';
        }

        public function description(): string
        {
            return 'Test check';
        }

        public function run(ReviewContext $context): array
        {
            return [];
        }

        // Expose protected methods for testing
        public function testMatchPattern(string $content, string $pattern): array
        {
            return $this->matchPattern($content, $pattern);
        }

        public function testResult(string $file, ?int $line, string $problem, string $solution, ?string $snippet = null): CheckResult
        {
            return $this->result($file, $line, $problem, $solution, $snippet);
        }

        public function testFileRelativePath(string $absolutePath, string $basePath): string
        {
            return $this->fileRelativePath($absolutePath, $basePath);
        }

        public function testGetSnippet(string $content, int $line, int $context = 2): string
        {
            return $this->getSnippet($content, $line, $context);
        }
    };
});

it('matches patterns with line numbers', function () {
    $content = "line one\ndd(\$foo);\nline three\ndump(\$bar);";
    $matches = $this->check->testMatchPattern($content, '/\b(dd|dump)\s*\(/');

    expect($matches)->toHaveCount(2)
        ->and($matches[0]['line'])->toBe(2)
        ->and($matches[1]['line'])->toBe(4);
});

it('creates check result with correct properties', function () {
    $result = $this->check->testResult('app/Test.php', 10, 'Problem found.', 'Fix it.');

    expect($result)->toBeInstanceOf(CheckResult::class)
        ->and($result->ruleId)->toBe('F-TEST-001')
        ->and($result->severity)->toBe('warning')
        ->and($result->file)->toBe('app/Test.php')
        ->and($result->line)->toBe(10)
        ->and($result->problem)->toBe('Problem found.')
        ->and($result->solution)->toBe('Fix it.');
});

it('generates relative file paths', function () {
    $relative = $this->check->testFileRelativePath('/home/user/project/app/Models/User.php', '/home/user/project');

    expect($relative)->toBe('app/Models/User.php');
});

it('generates code snippets with context', function () {
    $content = "line 1\nline 2\nline 3\nline 4\nline 5\nline 6\nline 7";
    $snippet = $this->check->testGetSnippet($content, 4, 1);

    expect($snippet)->toContain('line 3')
        ->toContain('line 4')
        ->toContain('line 5')
        ->toContain('> 4|');
});

it('returns empty matches for non-matching patterns', function () {
    $content = 'nothing to see here';
    $matches = $this->check->testMatchPattern($content, '/dd\(\)/');

    expect($matches)->toBeEmpty();
});
