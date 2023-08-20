<?php

namespace MaaximOne\LaAdmin\Classes\Page;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

class Page
{
    public static ?string $_icon = null;
    public object $_page;
    protected string $_pageName;
    protected array $_fields = [];

    public function __construct($_page)
    {
        $this->_pageName = $_page;

        $this->_page = (object)[
            'fields_forget' => [],
            'primaryKey' => null,
            'type' => 'table',
            'delete' => true,
            'title' => null,
            'model' => null,
            'edit' => true,
            'add' => true,
        ];
    }

    /**
     * @param string|null $icon
     * @return Page
     */
    public function setIcon(?string $icon): Page
    {
        self::$_icon = $icon;
        return $this;
    }

    public function setTitle(?string $title): Page
    {
        $this->_page->title = $title;
        return $this;
    }

    public function setAddMode(bool $mode = true): Page
    {
        $this->_page->add = $mode;
        return $this;
    }

    public function setEditMode(bool $mode = true): Page
    {
        $this->_page->edit = $mode;
        return $this;
    }

    public function setDeleteMode(bool $mode = true): Page
    {
        $this->_page->delete = $mode;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->_fields;
    }

    public function setFieldsForget(array $fields): Page
    {
        $this->_page->fields_forget = $fields;
        return $this;
    }

    public function setModel(string $model): Page
    {
        $this->_page->model = $model;
        $this->_page->primaryKey = (new $model)->getKeyName();

        $fields = Schema::getColumnListing((new $model)->getTable());

        foreach ($this->_page->fields_forget as $forget) {
            foreach ($fields as $key_field => $field) {
                if ($field == $forget) Arr::forget($fields, $key_field);
            }
        }

        foreach ($fields as $field) {
            $this->_fields = Arr::add($this->_fields, $field,
                (new Field())->setField(
                    $field, __($field),
                    Schema::getColumnType((new $model)->getTable(), $field)
                )
            );
        }

        return $this;
    }

    public function field(string $name, callable $closure): Page
    {
        if (Arr::has($this->_fields, $name) && is_callable($closure)) {
            $this->_fields[$name] = $closure($this->_fields[$name]);
        }

        return $this;
    }

    public function fileField(string $name, callable $closure): Page
    {
        if (Arr::has($this->_fields, $name) && is_callable($closure)) {
            $field = (new FileField())
                ->setField(
                    $this->_fields[$name]->_name,
                    $this->_fields[$name]->_label,
                    $this->_fields[$name]->_type,
                    $this->_fields[$name]->_validationRules
                )
                ->setDisabled($this->_fields[$name]->_disabled)
                ->setReadonly($this->_fields[$name]->_readonly)
                ->setShow($this->_fields[$name]->_show)
                ->setType('file');

            $field = $closure($field);

            Arr::forget($this->_fields, $name);

            $this->_fields['files'][$name] = $field;
        }

        return $this;
    }

    public function __toResponse(): object
    {
        $page = $this->_page;
        $page->name = $this->_pageName;
        $page->icon = self::$_icon;
        $page->fields = [];

        foreach ($this->_fields as $key => $field) {
            if ($key != 'files') $page->fields[$key] = $field->__toResponse();
        }

        if (Arr::has($this->_fields, 'files')) {
            foreach ($this->_fields['files'] as $key => $file) {
                $page->fields['files'][$key] = $file->__toResponse();
            }
        }

        return $page;
    }
}
