<?php

declare(strict_types=1);

namespace Fortress\Review;

abstract class BaseCheck implements FortressCheck
{
    /**
     * @return iterable<\SplFileInfo>
     */
    protected function scanFiles(ReviewContext $context, string $extension = 'php'): iterable
    {
        return match ($extension) {
            'php' => $context->phpFiles(),
            'js', 'ts' => $context->jsFiles(),
            'vue' => $context->vueFiles(),
            'blade' => $context->bladeFiles(),
            'migration' => $context->migrationFiles(),
            default => $context->phpFiles(),
        };
    }

    /**
     * @return array<int, array{line: int, match: string}>
     */
    protected function matchPattern(string $content, string $pattern): array
    {
        $matches = [];
        $lines = explode("\n", $content);

        foreach ($lines as $index => $line) {
            if (preg_match($pattern, $line, $m)) {
                $matches[] = ['line' => $index + 1, 'match' => $m[0]];
            }
        }

        return $matches;
    }

    protected function result(
        string $file,
        ?int $line,
        string $problem,
        string $solution,
        ?string $snippet = null,
    ): CheckResult {
        return new CheckResult(
            ruleId: $this->ruleId(),
            severity: $this->severity(),
            file: $file,
            line: $line,
            problem: $problem,
            solution: $solution,
            snippet: $snippet,
        );
    }

    protected function fileRelativePath(string $absolutePath, string $basePath): string
    {
        return ltrim(str_replace($basePath, '', $absolutePath), '/\\');
    }

    protected function getSnippet(string $content, int $line, int $context = 2): string
    {
        $lines = explode("\n", $content);
        $start = max(0, $line - 1 - $context);
        $end = min(count($lines) - 1, $line - 1 + $context);

        $snippet = '';
        for ($i = $start; $i <= $end; $i++) {
            $lineNum = $i + 1;
            $marker = ($lineNum === $line) ? '> ' : '  ';
            $snippet .= $marker.$lineNum.'| '.$lines[$i]."\n";
        }

        return rtrim($snippet);
    }
}
