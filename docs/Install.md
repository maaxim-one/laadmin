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

## Настройка модели пользователя

LaAdminPanel использует интерфейс `CanResetPassword` в модели пользователя.
Вам нужно его реализовать:

```php
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements CanResetPassword
{
    //
}
```

**LaAdminPanel** только отправляет письмо на почту пользователя.
Далее вам нужно реализовать функционал обработки по
оф. [Документации](https://laravel.com/docs/10.x/passwords#resetting-the-password).

## Вход в LaAdminPanel

Перейдите на страницу авторизации по адресу `http://loc.site/admin/login`.
<br>
По умолчанию логин: `admin` пароль: `123`

Дальше >> [Подключение WebSockets](WebSockets.md)
