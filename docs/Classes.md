# Классы

Описание фасадов и классов **LaAdminPanel**.

1. [Facade - LaAdminPage](#facade-laadminpage)
2. [LaAdminPage](#laadminpage)
3. [Page](#page)
4. [Field](#field)
5. [FileField](#filefield)

## Facade LaAdminPage

namespace: `MaaximOne\LaAdmin\Facades`

`class LaAdminPage extends Facade {}` - фасад для класса
[`MaaximOne\LaAdmin\Classes\Page\LaAdminPage`](#laadminpage)

## LaAdminPage

namespace: `MaaximOne\LaAdmin\Classes\Page`

`public function make(string $page): Page` - функция объявления новой
страницы.

> Параметры:<br>
> `string $page` - ключ страницы используется только для
> взаимодействия внутри приложения. Не должно быть пробелов/знаков и кириллицы.

`public function getPages(): object` - возвращает все страницы

`public function getPage(string $page): Page` - возвращает определённую страницу
> Параметры:<br>
> `string $page` - ключ страницы

## Page

namespace: `MaaximOne\LaAdmin\Classes\Page`

Класс - объект самой страницы. При инициализации таблицы БД считывании полей,
параметр name - название поля как в БД, label автоматически определяется по
названию поля от локализации. Т.е. если поле называется 'title' то в lang файле
его нужно прописать как нужно для отображения. Также label можно установить вручную.

`public function setIcon(?string $icon): Page` - устанавливает иконку
> Параметры:<br>
> `?string $icon` - класс иконки от [***MaterialDesignIcons***](https://pictogrammers.com/library/mdi/)

`public function setTitle(?string $title): Page` - устанавливает заголовок
> Параметры:<br>
> `?string $title` - строка

`public function setAddMode(bool $mode = true): Page` - устанавливает режим
взаимодействия, можно добавлять новые записи или нет

> Параметры:<br>
> `bool $mode = true`

`public function setEditMode(bool $mode = true): Page` - устанавливает режим
взаимодействия, можно редактировать записи или нет

> Параметры:<br>
> `bool $mode = true`

`public function setDeleteMode(bool $mode = true): Page` - устанавливает режим
взаимодействия, можно удалять записи или нет

> Параметры:<br>
> `bool $mode = true`

`public function getFields(): array` - возвращает массив полей
> Возвращает:
> ```php
> [
>    'field' => [
>        'name' => 'name',
>        'label' => 'label',
>        'type' => 'type',
>        'readonly' => 'readonly',
>        'disabled' => 'disabled',
>        'show' => 'show',
>        'validationRules' => 'validationRules'
>    ]
> ]
> ```

`public function setFieldsForget(array $fields): Page` - устанавливает поля,
с которыми не будет какого либо взаимодействия, они не будут добавлены в
список полей. Этот метод должен быть определён до того как будет инициализирована модель.

> Параметры:<br>
> `array $fields` - передается массив с наименованиями полей в БД

`public function setModel(string $model): Page` - установка модели для страницы.
При вызове этой функции будет сразу выполнено считывание таблицы БД.

> Параметры:<br>
> `string $model` - пространственный путь модели. Пример: `App\Models\User::class`

`public function field(string $name, callable $closure): Page` - определение правил для поля

> Параметры:<br>
> `string $name` - название поля в БД<br>
> `callable $closure` - функция для определения параметров поля

> Пример:
>```php
> LaAdminPage::make('example')
>     ->field('field', function (\MaaximOne\LaAdmin\Classes\Page\Field $field) {
>        return $field->setDisabled(true)
>            ->setShow(false);
>     });
>```

`public function fileField(string $name, callable $closure): Page` - объявление поля
файловым и определение правил для поля.

> Параметры:<br>
> `string $name` - название поля в БД<br>
> `callable $closure` - функция для определения параметров поля

> Пример:
>```php
> LaAdminPage::make('example')
>     ->fileField('field', function (MaaximOne\LaAdmin\Classes\Page\FileField $field) {
>        return $field
>            ->setPath(public_path('media'))
>            ->setPublicPath('media')
>            ->setValidationRulesAdd('required');
>     });
>```

`public function initRules(): void` - инициализация правил роли для страницы

## Field

namespace: `MaaximOne\LaAdmin\Classes\Page`

Класс `Field` это оболочка для каждого поля.

`public function setName(string $name): Field` - установить название поля в БД.
Устанавливается автоматически. Лучше не трогать))

> Параметры:<br>
> `string $name`

`public function setLabel(string $label): Field` - установить Label значение для поля.
Название которые выводиться для пользователя. Значение по умолчанию: `__($name)`

> Параметры:<br>
> `string $label`

`public function setType(string $type): Field` - установить тип поля.
По умолчанию равен типу поля в БД.

> Параметры:<br>
> `string $type`

`public function setReadonly(bool $readonly): Field` - правило, поле только для чтения

> Параметры:<br>
> `bool $readonly = false`

`public function setDisabled(bool $disabled): Field` - правило, поле неактивно

> Параметры:<br>
> `bool $disabled = false`

`public function setShow(bool $show): Field` - правило, показывать поле или нет

> Параметры:<br>
> `bool $show = true`

`public function setShowInTable(bool $showInTable): Field` - правило, показывать поле в таблице или нет

> Параметры:<br>
> `bool $showInTable = true`

`public function setValidationRules(null|string|array $validationRules): Field` -
устанавливаются общие правила для валидации поля.

> Параметры:<br>
> `null|string|array $validationRules` - список правил в соответствии с оф.
> документацией [Laravel Validation](https://laravel.com/docs/10.x/validation)

`public function setValidationRulesAdd(array|string|null $validationRulesAdd): Field` -
устанавливаются правила при добавлении записи валидации поля.

> Параметры:<br>
> `null|string|array $validationRulesAdd` - список правил в соответствии с оф.
> документацией [Laravel Validation](https://laravel.com/docs/10.x/validation)

`public function setValidationRulesEdit(array|string|null $validationRulesEdit): Field` -
устанавливаются правила при редактировании записи валидации поля.

> Параметры:<br>
> `null|string|array $validationRulesEdit` - список правил в соответствии с оф.
> документацией [Laravel Validation](https://laravel.com/docs/10.x/validation)

`public function setField(string $name, string $label, string $type = 'string', null|string|array $validationRules = null): Field` -
установить поле

> Параметры:<br>
> `string $name` - название поля в БД<br>
> `string $label` - Label поля<br>
> `string $type` - тип поля<br>
> `null|string|array $validationRules` - правила для валидации

## FileField

namespace: `MaaximOne\LaAdmin\Classes\Page`

Класс FileField унаследует класс Field. Является оболочкой для полей типа файл.
> **Внимание!** <br>
> Поле которое вы определили как файловое в БД должно иметь тип **JSON**, **обязательно**!
> В модели страницы у вас должен быть определен мутатор. Оф.
> документация [Laravel Eloquent: Mutators & Casting](https://laravel.com/docs/10.x/eloquent-mutators). Пример:
> ```php
> use Illuminate\Database\Eloquent\Casts\Attribute;
>
> protected function fieldName(): Attribute
> {
>     return Attribute::make(
>         get: fn($value) => json_decode($value),
>         set: fn($value) => json_encode($value)
>     );
> }
> ```

`public function setPath(?string $path): FileField` - установить путь для загрузки файлов.
Пример: `public_path('path/to/dir')`, так же и `storage_path`. Обязательно параметр.

`public function setPublicPath(?string $public_path): FileField` - установить путь в точке монтирования
т.е. `public/path/to/dir`. Пример: `'path/to/dir'`.

