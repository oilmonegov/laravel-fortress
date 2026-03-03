<?php

declare(strict_types=1);

namespace Fortress\Review\Checks\P01;

use Fortress\Review\BaseCheck;
use Fortress\Review\CheckResult;
use Fortress\Review\ReviewContext;

class FileUploadCheck extends BaseCheck
{
    public function id(): string
    {
        return 'file_upload';
    }

    public function ruleId(): string
    {
        return 'F-P01-012';
    }

    public function part(): string
    {
        return 'P01';
    }

    public function severity(): string
    {
        return 'warning';
    }

    public function description(): string
    {
        return 'Detect file upload handling without validation or stored in public directories';
    }

    /** @return CheckResult[] */
    public function run(ReviewContext $context): array
    {
        $results = [];

        foreach ($context->phpFiles() as $file) {
            $path = $file->getRealPath();
            $content = $context->content($path);
            $relativePath = $this->fileRelativePath($path, $context->basePath());

            // store() to public disk without validation context
            $matches = $this->matchPattern($content, '/->store\s*\([^)]*[\'"]public[\'"]/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'File upload stored to public disk which is directly web-accessible.',
                    'Store uploaded files on a private disk and serve through a controller with authorization checks.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // move() to public_path
            $matches = $this->matchPattern($content, '/->move\s*\(\s*public_path\s*\(/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'File moved to public directory, making it directly accessible.',
                    'Use `Storage::disk(\'local\')->putFile()` to store in a private location.',
                    $this->getSnippet($content, $match['line']),
                );
            }

            // getClientOriginalExtension without mimes/mimetypes validation
            $matches = $this->matchPattern($content, '/getClientOriginalExtension\s*\(\)/');
            foreach ($matches as $match) {
                $results[] = $this->result(
                    $relativePath,
                    $match['line'],
                    'Using client-reported file extension which can be spoofed.',
                    'Validate using `mimes` or `mimetypes` validation rules and use `guessExtension()` for server-side MIME detection.',
                    $this->getSnippet($content, $match['line']),
                );
            }
        }

        return $results;
    }
}
