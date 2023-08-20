<?php

namespace MaaximOne\LaAdmin\Classes\Page;

class Field
{
    public string $_name;
    public string $_label;
    public string $_type;
    public bool $_readonly = false;
    public bool $_disabled = false;
    public bool $_show = true;
    public string|array|null $_validationRules = null;
    public string|array|null $_validationRulesEdit = null;
    public string|array|null $_validationRulesAdd = null;

    public function setReadonly(bool $readonly): Field
    {
        $this->_readonly = $readonly;
        return $this;
    }

    public function setDisabled(bool $disabled): Field
    {
        $this->_disabled = $disabled;
        return $this;
    }

    public function setShow(bool $show): Field
    {
        $this->_show = $show;
        return $this;
    }

    /**
     * @param array|string|null $validationRulesAdd
     * @return Field
     */
    public function setValidationRulesAdd(array|string|null $validationRulesAdd): Field
    {
        $this->_validationRulesAdd = $validationRulesAdd;
        return $this;
    }

    /**
     * @param array|string|null $validationRulesEdit
     * @return Field
     */
    public function setValidationRulesEdit(array|string|null $validationRulesEdit): Field
    {
        $this->_validationRulesEdit = $validationRulesEdit;
        return $this;
    }

    public function setField(
        string            $name,
        string            $label,
        string            $type = 'string',
        null|string|array $validationRules = null
    ): Field
    {
        $this->setName($name);
        $this->setLabel($label);
        $this->setType($type);
        $this->setValidationRules($validationRules);

        return $this;
    }

    public function setName(string $name): Field
    {
        $this->_name = $name;
        return $this;
    }

    public function setLabel(string $label): Field
    {
        $this->_label = $label;
        return $this;
    }

    public function setType(string $type): Field
    {
        $this->_type = $type;
        return $this;
    }

    public function setValidationRules(null|string|array $validationRules): Field
    {
        $this->_validationRules = $validationRules;
        return $this;
    }

    public function __toResponse(): object
    {
        return (object)[
            'name' => $this->_name,
            'label' => $this->_label,
            'type' => $this->_type,
            'readonly' => $this->_readonly,
            'disabled' => $this->_disabled,
            'show' => $this->_show,
            'validationRules' => $this->_validationRules,
        ];
    }
}
