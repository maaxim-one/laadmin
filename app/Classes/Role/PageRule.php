<?php

namespace MaaximOne\LaAdmin\Classes\Role;

use Illuminate\Support\Collection;

class PageRule
{
    protected object $_defaultValues;
    protected ?string $_abbreviation = null;
    protected bool $_accept = false;
    protected bool $_customParams = false;
    protected object $_params;

    public function __construct()
    {
        $this->_defaultValues = (object)[
            'add' => (object)[
                'value' => false,
                'abbreviation' => 'Добавление'
            ],
            'edit' => (object)[
                'value' => false,
                'abbreviation' => 'Редактирование'
            ],
            'delete' => (object)[
                'value' => false,
                'abbreviation' => 'Удаление'
            ]
        ];

        $this->_params = (object)[];
    }

    public function getRules(): object
    {
        return $this->_params;
    }

    public function getAccept(): bool
    {
        return $this->_accept;
    }

    public function setAccept(bool $value): static
    {
        $this->_accept = $value;
        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->_abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): static
    {
        $this->_abbreviation = $abbreviation;
        return $this;
    }

    public function setAddRule(bool $value = false): PageRule
    {
        $this->_params->add = (object)[
            'value' => $value,
            'abbreviation' => $this->_defaultValues->add->abbreviation
        ];
        return $this;
    }

    public function setEditRule(bool $value = false): PageRule
    {
        $this->_params->edit = (object)[
            'value' => $value,
            'abbreviation' => $this->_defaultValues->edit->abbreviation
        ];
        return $this;
    }

    public function setDeleteRule(bool $value = false): PageRule
    {
        $this->_params->delete = (object)[
            'value' => $value,
            'abbreviation' => $this->_defaultValues->delete->abbreviation
        ];

        return $this;
    }

    public function setCustomRule(string $rule, bool $val, string $abbreviation): PageRule
    {
        $this->_params->{$rule} = (object)[
            'value' => $val,
            'abbreviation' => $abbreviation
        ];
        return $this;
    }

    public function setCustomParams(array $params): PageRule
    {
        $this->_customParams = true;
        $this->_params = (object)$params;
        return $this;
    }

    public function __toResponse(): array
    {
        return collect($this->getParams())->toArray();
    }

    public function getParams(): Collection
    {
        if (!$this->_customParams) {
            return collect($this->_defaultValues)->replaceRecursive(
                collect($this->_params)
            );
        } else return collect($this->_params);
    }
}
