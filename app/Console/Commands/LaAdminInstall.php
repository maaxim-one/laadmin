<?php

namespace MaaximOne\LaAdmin\Console\Commands;

use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class LaAdminInstall extends Command
{
    protected $signature = 'laadmin:install';

    protected $description = 'Установка LaAdminPanel';

    protected array $_types = [
        'all' => [
            'publicConfigFile' => 'Публикация файла конфигурации',
            'publicSeedersFiles' => 'Публикация сидеров(seeders)',
            'publicResources' => 'Публикация ресурсов LaAdminPanel',
            'publicPages' => 'Публикация файла страниц',
        ],
        'config' => [
            'publicConfigFile' => 'Публикация файла конфигурации'
        ],
        'database' => [
            'setDataBase' => 'Публикация файлов БД и автоматическое выполнение "посевов"'
        ],
        'resources' => [
            'publicResources' => 'Публикация стилей и скриптов LaAdminPanel'
        ],
    ];
    protected array $_seedersClassesName = [];

    public function handle(): void
    {
        $this->info("Привет! Сейчас мы выполним установку LaAdminPanel. Тебе доступны следующие"
            . " варианты установки, выбери какой тебе нужен.");
        $this->info(
            " • all - Публикация всех файлов\n"
            . " • config - Публикация файла конфигурации\n"
            . " • database - Публикация файлов БД и автоматическое выполнение\n"
            . " • resources - Публикация стилей и скриптов LaAdmin\n"
        );

        $type = $this->anticipate('Так что?', ['all', 'config', 'database', 'resources']);

        if (Arr::has($this->_types, $type)) {
            foreach ($this->_types[$type] as $callback => $description) {
                $this->info($description);
                $this->$callback();
            }
            $this->info('Готово');
        }
    }

    public function publicConfigFile(): void
    {
        copy(
            __DIR__ . '/../../../config/la-admin.php',
            config_path('la-admin.php')
        );
    }

    public function setDataBase(): void
    {
        Artisan::call('migrate');

        $this->publicSeedersFiles();

        foreach ($this->_seedersClassesName as $item) {
            Artisan::call("db:seed --class={$item}");
        }
    }

    protected function publicSeedersFiles(): void
    {
        $files = (new Filesystem)->allFiles(__DIR__ . '/../../../database/seeds', true);

        if (!is_dir($dir = database_path('seeders'))) {
            mkdir($dir, 0755, true);
        }

        collect($files)->filter(function (SplFileInfo $file) {
            $ex = explode('.', $file->getBasename());
            if ($ex[count($ex) - 1] == 'stub') {
                return true;
            }

            return false;
        })->each(function (SplFileInfo $file) use ($dir) {
            $this->_seedersClassesName[] = $file->getBasename('.stub');

            $this->line($dir . '/' . $file->getBasename('.stub') . '.php');

            copy(
                $file->getRealPath(),
                $dir . '/' . $file->getBasename('.stub') . '.php'
            );
        });
    }

    protected function publicResources(): void
    {
        $this->copyDir(
            __DIR__ . '/../../../resources/laadmin',
            public_path('laadmin')
        );
    }

    protected function publicPages(): void
    {
        if (!is_dir($dir = app_path('laadmin'))) {
            mkdir($dir, 0755, true);
        }

        copy(
            __DIR__ . '/../../../routes/pages.stub',
            $dir . '/pages.php'
        );
    }

    protected function copyDir($from, $to): void
    {
        $dir = opendir($from);
        @mkdir($to);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($from . '/' . $file)) {
                    $this->copyDir($from . '/' . $file, $to . '/' . $file);
                } else {
                    copy($from . '/' . $file, $to . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
