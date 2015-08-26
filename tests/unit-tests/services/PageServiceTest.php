<?php

class PageServiceTest extends TiendaNubeTestCase {

    private $pageService;

    public function setUp() {
        parent::setUp();
        $this->pageService = new PageService(666);
    }

    public function test_save_published() {
        $page = $this->pageService->save(true);
        $this->assertEquals(1, $page->publish);
    }

    public function test_save_unpublished() {
        $page = $this->pageService->save(false);
        $this->assertEquals("", $page->publish);
    }

    public function test_save_and_find_1() {
        $page = $this->pageService->save(true);
        $page = Page::find($page->id);
        $this->assertEquals(1, $page->publish);
    }

    public function test_save_and_find_2() {
        $page = $this->pageService->save(false);
        $page = Page::find($page->id);
        $this->assertEquals("", $page->publish);
    }

    public function test_update_and_find() {
        $page = $this->pageService->save(true);
        $page->publish = false;
        $this->pageService->update($page);
        $page = Page::find($page->id);
        $this->assertEquals("", $page->publish);
    }

    public function test_save_language() {
        $pageLangs = [
            'en_US' => new PageLangDTO('name', 'content', 'seo title', 'seo description', 'handle')
        ];
        $page = $this->pageService->save(true, $pageLangs);
        $pageLang = $page->{'en_US'};
        $this->assertEquals('name', $pageLang->name);
        $this->assertEquals('content', $pageLang->content);
        $this->assertEquals('seo title', $pageLang->seo_title);
        $this->assertEquals('seo description', $pageLang->seo_description);
        $this->assertEquals('handle', $pageLang->{'nice-name'});
    }

    public function test_save_multiple_languages() {
        $pageLangs = [
            'en_US' => new PageLangDTO('name 1', 'content 1', 'seo title 1', 'seo description 1', 'handle 1'),
            'pt_BR' => new PageLangDTO('name 2', 'content 2', 'seo title 2', 'seo description 2', 'handle 2'),
        ];
        $page = $this->pageService->save(true, $pageLangs);

        // en_US
        $pageLang = $page->{'en_US'};
        $this->assertEquals('name 1', $pageLang->name);
        $this->assertEquals('content 1', $pageLang->content);
        $this->assertEquals('seo title 1', $pageLang->seo_title);
        $this->assertEquals('seo description 1', $pageLang->seo_description);
        $this->assertEquals('handle 1', $pageLang->{'nice-name'});

        // pt_BR
        $pageLang = $page->{'pt_BR'};
        $this->assertEquals('name 2', $pageLang->name);
        $this->assertEquals('content 2', $pageLang->content);
        $this->assertEquals('seo title 2', $pageLang->seo_title);
        $this->assertEquals('seo description 2', $pageLang->seo_description);
        $this->assertEquals('handle 2', $pageLang->{'nice-name'});
    }

    public function test_save_transaction() {
        $pageLangs = [
            'en_US' => new PageLangDTO('name 1', 'content 1', 'seo title 1', 'seo description 1', 'handle 1'),
            'pt_BR' => null, // Will cause an exception
        ];
        try {
            $this->pageService->save(true, $pageLangs);
        } catch (Exception $ignore) {
        }
        $pages = Page::all();
        $this->assertEmpty($pages);
    }

    public function test_update_language() {
        $pageLangsOld = [
            'en_US' => new PageLangDTO('name', 'content', 'seo title', 'seo description', 'handle')
        ];
        $page = $this->pageService->save(true, $pageLangsOld);
        $pageLangsNew = [
            'en_US' => new PageLangDTO('name 1', 'content 1', 'seo title 1', 'seo description 1', 'handle 1')
        ];
        $this->pageService->update($page, $pageLangsNew);
        $pageLang = $page->{'en_US'};
        $this->assertEquals('name 1', $pageLang->name);
        $this->assertEquals('content 1', $pageLang->content);
        $this->assertEquals('seo title 1', $pageLang->seo_title);
        $this->assertEquals('seo description 1', $pageLang->seo_description);
        $this->assertEquals('handle 1', $pageLang->{'nice-name'});
    }

    public function test_update_multiple_languages() {
        $pageLangsOld = [
            'en_US' => new PageLangDTO('name 1', 'content 1', 'seo title 1', 'seo description 1', 'handle 1'),
            'pt_BR' => new PageLangDTO('name 2', 'content 2', 'seo title 2', 'seo description 2', 'handle 2'),
        ];
        $page = $this->pageService->save(true, $pageLangsOld);
        $pageLangsNew = [
            'en_US' => new PageLangDTO('name 11', 'content 11', 'seo title 11', 'seo description 11', 'handle 11'),
            'pt_BR' => new PageLangDTO('name 22', 'content 22', 'seo title 22', 'seo description 22', 'handle 22'),
        ];
        $this->pageService->update($page, $pageLangsNew);

        // en_US
        $pageLang = $page->{'en_US'};
        $this->assertEquals('name 11', $pageLang->name);
        $this->assertEquals('content 11', $pageLang->content);
        $this->assertEquals('seo title 11', $pageLang->seo_title);
        $this->assertEquals('seo description 11', $pageLang->seo_description);
        $this->assertEquals('handle 11', $pageLang->{'nice-name'});

        // pt_BR
        $pageLang = $page->{'pt_BR'};
        $this->assertEquals('name 22', $pageLang->name);
        $this->assertEquals('content 22', $pageLang->content);
        $this->assertEquals('seo title 22', $pageLang->seo_title);
        $this->assertEquals('seo description 22', $pageLang->seo_description);
        $this->assertEquals('handle 22', $pageLang->{'nice-name'});
    }

    public function test_update_transaction() {
        $pageLangsOld = [
            'en_US' => new PageLangDTO('name 1', 'content 1', 'seo title 1', 'seo description 1', 'handle 1'),
            'pt_BR' => new PageLangDTO('name 2', 'content 2', 'seo title 2', 'seo description 2', 'handle 2'),
        ];
        $page = $this->pageService->save(true, $pageLangsOld);
        $pageLangsNew = [
            'en_US' => new PageLangDTO('name 11', 'content 11', 'seo title 11', 'seo description 11', 'handle 11'),
            'pt_BR' => null, // Will cause an exception
        ];
        try {
            $this->pageService->update($page, $pageLangsNew);
        } catch (Exception $ignore) {
        }

        $page = Page::find($page->id);
        // en_US
        $pageLang = $page->{'en_US'};
        $this->assertEquals('name 1', $pageLang->name);
        $this->assertEquals('content 1', $pageLang->content);
        $this->assertEquals('seo title 1', $pageLang->seo_title);
        $this->assertEquals('seo description 1', $pageLang->seo_description);
        $this->assertEquals('handle 1', $pageLang->{'nice-name'});

        // pt_BR
        $pageLang = $page->{'pt_BR'};
        $this->assertEquals('name 2', $pageLang->name);
        $this->assertEquals('content 2', $pageLang->content);
        $this->assertEquals('seo title 2', $pageLang->seo_title);
        $this->assertEquals('seo description 2', $pageLang->seo_description);
        $this->assertEquals('handle 2', $pageLang->{'nice-name'});
    }

}
