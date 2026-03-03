<?php

declare(strict_types=1);

namespace Fortress\Review;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ReviewContext
{
    /** @var SplFileInfo[]|null */
    private ?array $phpFiles = null;

    /** @var SplFileInfo[]|null */
    private ?array $jsFiles = null;

    /** @var SplFileInfo[]|null */
    private ?array $vueFiles = null;

    /** @var SplFileInfo[]|null */
    private ?array $bladeFiles = null;

    /** @var SplFileInfo[]|null */
    private ?array $migrationFiles = null;

    /** @var array<string, string> */
    private array $contentCache = [];

    public function __construct(
        private readonly string $basePath,
    ) {}

    /** @return SplFileInfo[] */
    public function phpFiles(): array
    {
        if ($this->phpFiles === null) {
            $dirs = array_filter([
                $this->basePath.'/app',
                $this->basePath.'/config',
                $this->basePath.'/database',
                $this->basePath.'/routes',
            ], 'is_dir');

            if (empty($dirs)) {
                $this->phpFiles = [];

                return $this->phpFiles;
            }

            $this->phpFiles = iterator_to_array(
                (new Finder)->files()->name('*.php')->notName('*.blade.php')->in($dirs),
                false,
            );
        }

        return $this->phpFiles;
    }

    /** @return SplFileInfo[] */
    public function jsFiles(): array
    {
        if ($this->jsFiles === null) {
            $dirs = array_filter([
                $this->basePath.'/resources/js',
                $this->basePath.'/resources/ts',
            ], 'is_dir');

            if (empty($dirs)) {
                $this->jsFiles = [];

                return $this->jsFiles;
            }

            $this->jsFiles = iterator_to_array(
                (new Finder)->files()->name(['*.js', '*.ts', '*.jsx', '*.tsx'])->notPath('node_modules')->in($dirs),
                false,
            );
        }

        return $this->jsFiles;
    }

    /** @return SplFileInfo[] */
    public function vueFiles(): array
    {
        if ($this->vueFiles === null) {
            $dirs = array_filter([
                $this->basePath.'/resources/js',
                $this->basePath.'/resources/views',
            ], 'is_dir');

            if (empty($dirs)) {
                $this->vueFiles = [];

                return $this->vueFiles;
            }

            $this->vueFiles = iterator_to_array(
                (new Finder)->files()->name('*.vue')->notPath('node_modules')->in($dirs),
                false,
            );
        }

        return $this->vueFiles;
    }

    /** @return SplFileInfo[] */
    public function bladeFiles(): array
    {
        if ($this->bladeFiles === null) {
            $dir = $this->basePath.'/resources/views';

            if (! is_dir($dir)) {
                $this->bladeFiles = [];

                return $this->bladeFiles;
            }

            $this->bladeFiles = iterator_to_array(
                (new Finder)->files()->name('*.blade.php')->in($dir),
                false,
            );
        }

        return $this->bladeFiles;
    }

    /** @return SplFileInfo[] */
    public function migrationFiles(): array
    {
        if ($this->migrationFiles === null) {
            $dir = $this->basePath.'/database/migrations';

            if (! is_dir($dir)) {
                $this->migrationFiles = [];

                return $this->migrationFiles;
            }

            $this->migrationFiles = iterator_to_array(
                (new Finder)->files()->name('*.php')->in($dir),
                false,
            );
        }

        return $this->migrationFiles;
    }

    public function content(string $path): string
    {
        if (! isset($this->contentCache[$path])) {
            $this->contentCache[$path] = file_get_contents($path) ?: '';
        }

        return $this->contentCache[$path];
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    /** @return SplFileInfo[] */
    public function routeFiles(): array
    {
        $dir = $this->basePath.'/routes';

        if (! is_dir($dir)) {
            return [];
        }

        return iterator_to_array(
            (new Finder)->files()->name('*.php')->in($dir),
            false,
        );
    }

    /** @return SplFileInfo[] */
    public function configFiles(): array
    {
        $dir = $this->basePath.'/config';

        if (! is_dir($dir)) {
            return [];
        }

        return iterator_to_array(
            (new Finder)->files()->name('*.php')->in($dir),
            false,
        );
    }

    /** @return SplFileInfo[] */
    public function modelFiles(): array
    {
        $dir = $this->basePath.'/app/Models';

        if (! is_dir($dir)) {
            return [];
        }

        return iterator_to_array(
            (new Finder)->files()->name('*.php')->in($dir),
            false,
        );
    }

    /** @return SplFileInfo[] */
    public function controllerFiles(): array
    {
        $dir = $this->basePath.'/app/Http/Controllers';

        if (! is_dir($dir)) {
            return [];
        }

        return iterator_to_array(
            (new Finder)->files()->name('*.php')->in($dir),
            false,
        );
    }

    /** @return SplFileInfo[] */
    public function testFiles(): array
    {
        $dir = $this->basePath.'/tests';

        if (! is_dir($dir)) {
            return [];
        }

        return iterator_to_array(
            (new Finder)->files()->name('*.php')->in($dir),
            false,
        );
    }

    /** @return SplFileInfo[] */
    public function jobFiles(): array
    {
        $dir = $this->basePath.'/app/Jobs';

        if (! is_dir($dir)) {
            return [];
        }

        return iterator_to_array(
            (new Finder)->files()->name('*.php')->in($dir),
            false,
        );
    }

    /** @return SplFileInfo[] */
    public function actionFiles(): array
    {
        $dir = $this->basePath.'/app/Actions';

        if (! is_dir($dir)) {
            return [];
        }

        return iterator_to_array(
            (new Finder)->files()->name('*.php')->in($dir),
            false,
        );
    }
}
