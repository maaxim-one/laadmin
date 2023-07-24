# WebSockets

Бесплатный сервер WebSockets для
Laravel - [beyondcode/laravel-websockets](https://beyondco.de/docs/laravel-websockets/getting-started/introduction).
Установите через composer и настройте по оф. документации. Для работы со стороны клиента
используется [Laravel Echo](https://github.com/laravel/echo).

Echo настроен на `.env` файл.

Конфиг:

`/config/websockets.php`

```php
'apps' => [
    [
        'id' => env('PUSHER_APP_ID'),
        'name' => env('APP_NAME'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'path' => env('PUSHER_APP_PATH'),
        'capacity' => null,
        'enable_client_messages' => false,
        'enable_statistics' => false,
    ],
],
```

`/config/broadcasting.php`

```php
 'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'host' => env('PUSHER_HOST', '127.0.0.1'),
        'port' => env('PUSHER_PORT', 6001),
        'scheme' => env('PUSHER_SCHEME', 'http'),
        'encrypted' => env('PUSHER_SCHEME', 'http') === 'https',
        'curl_options' => [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]
    ]
],
```

`/.env`

```dotenv
PUSHER_APP_ID=1
PUSHER_APP_KEY="123456"
PUSHER_APP_SECRET="123"
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

`.env` - является ключевым, через него выполняется подключение **Laravel Echo** к серверу.
