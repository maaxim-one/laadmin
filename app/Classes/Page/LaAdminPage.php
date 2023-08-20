<?php

namespace MaaximOne\LaAdmin\Classes\Page;

class LaAdminPage
{
    public object $_pages;

    public function __construct()
    {
        $this->_pages = (object)[];
    }

    public function make(string $page): Page
    {
        return $this->_pages->{$page} = new Page($page);
    }

    /**
     * @return object
     */
    public function getPages(): object
    {
        return $this->_pages;
    }

    /**
     * @param string $page
     * @return Page
     */
    public function getPage(string $page): Page
    {
        return $this->_pages->{$page};
    }

    public function __toResponse(): array
    {
        $pages = [];

        foreach ($this->_pages as $page) {
            $pages[] = $page->__toResponse();
        }

        return $pages;
    }
}
