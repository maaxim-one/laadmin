# Страницы

Добавлять страниц в LaAdminPanel очень просто, нужно просто описать модель.

Наглядно:

Файл `app/laadmin/pages.php`

```php
use MaaximOne\LaAdmin\Classes\Page\FileField;
use MaaximOne\LaAdmin\Classes\Page\Field;

LaAdminPage::make('products')
    ->setModel(\App\Models\Product::class)
    ->setTitle('Товары')
    ->setIcon('mdi-ab-testing')
    ->field('product_id', function (Field $field) {
        return $field->setDisabled(true)
            ->setShow(false);
    })
    ->field('product_name', function (Field $field) {
        return $field->setValidationRules('required');
    })
    ->fileField('photos', function (FileField $field) {
        return $field
            ->setPath(storage_path('media/products'))
            ->setPublicPath('storage/media/products')
            ->setValidationRulesAdd('required');
    });
```

Просто, да? Давай рассмотрим детальнее.

`LaAdminPage` - это фасад для создания страниц в LaAdminPanel. Методом
`make` мы объявляем новую страницу, он принимает только один параметр -
ключ страницы для взаимодействия внутри приложения. Следом после объявления
`make` мы указываем модель для этой страницы - метод `setModel`, он
обязательно должен быть первым т.к. с него начинается 'стройка' страницы,
значение принимает одно - пространственный путь к модели как показано на
примере выше. После того как вы указали модель приложение автоматически
считывает все поля таблицы привязанной к модели.

Метод `setTitle` - устанавливает название страницы для отображения на
странице.

Метод `setIcon` - устанавливает иконку для страницы.

Метод `field` и `fileField` уже поинтереснее. Более подробно изучить >>
[тут](Classes.md)
