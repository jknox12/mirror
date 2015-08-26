<?php

class PageService {

    private $storeId;

    public function __construct($storeId) {
        $this->storeId = $storeId;
    }

    public function save($published, $pageLangs = []) {
        $pdo = DB::connection()->pdo;
        try {
            $pdo->beginTransaction();
            $page = $this->createPage();
            $page->publish = $published;
            $page->save();
            foreach ($pageLangs as $lang => $dto) {
                $pageLang = $this->createPageLang($lang, $dto);
                $pageLang->page_id = $page->id;
                $pageLang->save();
            }
            $pdo->commit();
            return $page;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function update(Page $page, $pageLangs = []) {
        $pdo = DB::connection()->pdo;
        try {
            $pdo->beginTransaction();
            $page->save();
            foreach ($pageLangs as $lang => $dto) {
                $pageLang = $page->$lang;
                $pageLang->title = $dto->name;
                $pageLang->content = $dto->content;
                $pageLang->seo_title = $dto->seoTitle;
                $pageLang->seo_description = $dto->seoDesc;
                $pageLang->{'nice-name'} = $dto->handle;
                $pageLang->save();
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    private function createPage() {
        $page = new Page();
        $page->store_id = $this->storeId;
        return $page;
    }

    private function createPageLang($lang, PageLangDTO $dto) {
        $pageLang = new Page_I18n();
        $pageLang->lang = $lang;
        $pageLang->title = $dto->name;
        $pageLang->content = $dto->content;
        $pageLang->seo_title = $dto->seoTitle;
        $pageLang->seo_description = $dto->seoDesc;
        $pageLang->{'nice-name'} = $dto->handle;
        return $pageLang;
    }

}
