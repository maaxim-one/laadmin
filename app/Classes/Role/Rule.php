<?php

namespace MaaximOne\LaAdmin\Classes\Role;

use Illuminate\Support\Collection;

class Rule
{
    protected object $_params;

    public function __construct()
    {
        $this->_params = (object)[];
    }

    public function group(string $group, string $abbreviation, PageRule $rules, bool $accept = false): static
    {
        $i = (object)[
            'abbreviation' => $abbreviation,
            'accept' => $accept,
            'params' => $rules->getParams()
        ];

        $this->_params->{$group} = $i;

        return $this;
    }

    public function getParams(): Collection
    {
        return collect($this->_params);
    }

    public function setParams(array $params): static
    {
        $this->_params = (object)$params;
        return $this;
    }
}
