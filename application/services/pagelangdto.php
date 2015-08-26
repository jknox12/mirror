<?php

class PageLangDTO {

    public $name;

    public $content;

    public $seoTitle;

    public $seoDesc;

    public $handle;

    public function __construct($name, $content, $seoTitle, $seoDesc, $handle) {
        $this->name = $name;
        $this->content = $content;
        $this->seoTitle = $seoTitle;
        $this->seoDesc = $seoDesc;
        $this->handle = $handle;
    }

}
