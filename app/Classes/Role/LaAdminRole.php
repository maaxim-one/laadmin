<?php

namespace MaaximOne\LaAdmin\Classes\Role;

class LaAdminRole
{
    public object $_rules;

    public function __construct()
    {
        $this->_rules = new \stdClass();
    }

    public function make(string $page): PageRule
    {
        return $this->_rules->{$page} = new PageRule();
    }

    /**
     * @return object
     */
    public function getRules(): object
    {
        return $this->_rules;
    }

    public function setRule(string $page, PageRule $role): static
    {
        $this->_rules->{$page} = $role;
        return $this;
    }

    public function __toResponse(): array
    {
        $rules = [];

        /**
         * @var $item PageRule
         */
        foreach ($this->_rules as $key => $item) {
            $rules[$key] = [
                'abbreviation' => \LaAdminRole::getRule($key)->getAbbreviation(),
                'accept' => \LaAdminRole::getRule($key)->getAccept(),
                'params' => \LaAdminRole::getRule($key)->__toResponse()
            ];
        }

        return $rules;
    }

    public function getRule(string $page): PageRule
    {
        return $this->_rules->{$page};
    }
}
