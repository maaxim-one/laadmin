# Установка LaAdminPanel

`composer.json`

```json
{
    "require": {
        "maaxim-one/laadmin": "1.x-dev"
    },
    "minimum-stability": "dev"
}
```

**Terminal:** `composer require maaxim-one/laadmin -W`

## Публикация файлов

**Terminal:** `php artisan laadmin:install`

При первой установке нужно выбрать 'all'. После обновления LaAdmin
нужно обязательно заново опубликовать 'resources'.

## Отлов и обработка ошибок

Отлов ошибок которые возникают во время работы сайта и сохранение в БД.
Нужно подключить событие для отлова ошибок.
**\App\Exceptions\Handler.php**

```php
public function register(): void
{
    $this->reportable(function (Throwable $e) {
        event(new \MaaximOne\LaAdmin\Events\ErrorReportEvent($e));
    });
}
```

Дальше >> [Подключение WebSockets](WebSockets.md)
