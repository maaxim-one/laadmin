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
//            'publicLinksFile' => 'Публикация файла маршрутов(ссылки)',
            'publicRoutesFile' => 'Публикация файла маршрутов(роуты)',
            'publicSeedersFiles' => 'Публикация сидеров(seeders)',
//            'publicResources' => 'Публикация ресурсов AdminPanel',
//            'publicLangFiles' => 'Публикация файлов русского языка',
        ],
        'config' => [
            'publicConfigFile' => 'Публикация файла конфигурации'
        ],
//        'links' => [
//            'publicLinksFile' => 'Публикация файла маршрутов(ссылки)'
//        ],
        'routes' => [
            'publicRoutesFile' => 'Публикация файла маршрутов(роуты)'
        ],
        'database' => [
            'setDataBase' => 'Публикация файлов БД и автоматическое выполнение'
        ],
//        'resources' => [
//            'publicResources' => 'Публикация стилей и скриптов LaAdmin'
//        ],
//        'language' => [
//            'publicLangFiles' => 'Публикация файлов русского языка'
//        ],
    ];
    protected array $_seedersClassesName = [];

    public function handle(): void
    {
        $this->info("Привет! Спасибо за твой выбор! Сейчас мы выполним установку LaAdmin. Тебе доступны следующие"
            . "варианты установки, выбери какой тебе нужен.");
        $this->info(
            " • all - Публикация всех файлов\n"
            . " • config - Публикация файла конфигурации\n"
//            . " • links\n"
            . " • routes - Публикация файла маршрутов(роуты)\n"
            . " • database - Публикация файлов БД и автоматическое выполнение\n"
//            . " • resources - Публикация стилей и скриптов LaAdmin\n"
//            . " • language\n"
        );

        $type = $this->anticipate('Так что?', ['all', 'config', 'routes', 'database']);

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

    public function publicRoutesFile(): void
    {
        copy(
            __DIR__ . '/../../../routes/admin.stub',
            base_path('routes/admin.php')
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
}
