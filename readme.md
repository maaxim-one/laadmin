# LaAdmin Panel

**Ещё очень сыро.**

**В разработке (In developing).**

Эту админку я делаю в первую очередь для себя. Если вдруг она тебе понравилась. Юзай :)

Пакеты которые добавлены в **LaAdmin**.

1. **@fortawesome/fontawesome-free**: ^6.4.0
2. **@mdi/font**: ^7.2.96
3. **vue-router**: ^4.2.2
4. **vuetify**: ^3.3.2
5. **axios**: ^1.1.2
6. **vuex**: ^4.1.0
7. **vue**: ^3.3.4

## Обработка ошибок

Отлов ошибок которые возникают во время работы сайта и сохранение в БД.
Нужно подключить событие для отловки ошибок.
**\App\Exceptions\Handler.php**

```php
public function register(): void
{
    $this->reportable(function (Throwable $e) {
        event(new \MaaximOne\LaAdmin\Events\ErrorReportEvent($e));
    });
}
```
