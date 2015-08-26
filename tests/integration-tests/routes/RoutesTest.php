<?php

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RoutesTest extends LaravelTestCase {

    public function test_post() {
        $response = $this->POST('/666/pages', [
            'name' => [
                'en_US' => 'Name',
                'es_AR' => 'Nombre',
                'pt_BR' => 'Nome'
            ],
            'content' => [
                'en_US' => 'Content',
                'es_AR' => 'Contenido',
                'pt_BR' => 'Conteúdo'
            ],
            'seo_title' => [
                'en_US' => 'SEO Title',
                'es_AR' => 'Título de SEO',
                'pt_BR' => 'Título para SEO'
            ],
            'seo_description' => [
                'en_US' => 'SEO Description',
                'es_AR' => 'Descripción de SEO',
                'pt_BR' => 'Descrição para SEO'
            ],
            'handle' => [
                'en_US' => 'url-us',
                'es_AR' => 'url-ar',
                'pt_BR' => 'url-br'
            ],
            'published' => "true"
        ]);

        $page = Page::find($response->id);
        $this->assertEquals(1, $page->publish);
        $this->assertNull($page->deleted_at);

        $pageLang = $page->{'en_US'};
        $this->assertEquals('Name', $pageLang->title);
        $this->assertEquals('Content', $pageLang->content);
        $this->assertEquals('SEO Title', $pageLang->seo_title);
        $this->assertEquals('SEO Description', $pageLang->seo_description);
        $this->assertEquals('url-us', $pageLang->{'nice-name'});

        $pageLang = $page->{'es_AR'};
        $this->assertEquals('Nombre', $pageLang->title);
        $this->assertEquals('Contenido', $pageLang->content);
        $this->assertEquals('Título de SEO', $pageLang->seo_title);
        $this->assertEquals('Descripción de SEO', $pageLang->seo_description);
        $this->assertEquals('url-ar', $pageLang->{'nice-name'});

        $pageLang = $page->{'pt_BR'};
        $this->assertEquals('Nome', $pageLang->title);
        $this->assertEquals('Conteúdo', $pageLang->content);
        $this->assertEquals('Título para SEO', $pageLang->seo_title);
        $this->assertEquals('Descrição para SEO', $pageLang->seo_description);
        $this->assertEquals('url-br', $pageLang->{'nice-name'});
    }

    public function test_put() {
        $pageService = new PageService(666);
        $page = $pageService->save(true,[
            'en_US' => new PageLangDTO(
                    'Name US', 'Content US', 'SEO Title US', 'SEO Description US', 'handle-us'),
            'es_AR' => new PageLangDTO(
                    'Name AR', 'Content AR', 'SEO Title AR', 'SEO Description AR', 'handle-ar'),
            'pt_BR' => new PageLangDTO(
                    'Name BR', 'Content BR', 'SEO Title BR', 'SEO Description BR', 'handle-br')
        ]);
        $this->PUT("/666/pages/$page->id", [
            'name' => [
                'en_US' => 'Name 1',
                'es_AR' => 'Name 2',
                'pt_BR' => 'Name 3',
            ],
            'content' => [
                'en_US' => 'Content 1',
                'es_AR' => 'Content 2',
                'pt_BR' => 'Content 3',
            ],
            'seo_title' => [
                'en_US' => 'SEO Title 1',
                'es_AR' => 'SEO Title 2',
                'pt_BR' => 'SEO Title 3',
            ],
            'seo_description' => [
                'en_US' => 'SEO Description 1',
                'es_AR' => 'SEO Description 2',
                'pt_BR' => 'SEO Description 3',
            ],
            'handle' => [
                'en_US' => 'handle-1',
                'es_AR' => 'handle-2',
                'pt_BR' => 'handle-3',
            ],
            'published' => "false"
        ]);

        $page = Page::find($page->id);
        $this->assertEquals("", $page->publish);
        $this->assertNull($page->deleted_at);

        $pageLang = $page->{'en_US'};
        $this->assertEquals('Name 1', $pageLang->title);
        $this->assertEquals('Content 1', $pageLang->content);
        $this->assertEquals('SEO Title 1', $pageLang->seo_title);
        $this->assertEquals('SEO Description 1', $pageLang->seo_description);
        $this->assertEquals('handle-1', $pageLang->{'nice-name'});

        $pageLang = $page->{'es_AR'};
        $this->assertEquals('Name 2', $pageLang->title);
        $this->assertEquals('Content 2', $pageLang->content);
        $this->assertEquals('SEO Title 2', $pageLang->seo_title);
        $this->assertEquals('SEO Description 2', $pageLang->seo_description);
        $this->assertEquals('handle-2', $pageLang->{'nice-name'});

        $pageLang = $page->{'pt_BR'};
        $this->assertEquals('Name 3', $pageLang->title);
        $this->assertEquals('Content 3', $pageLang->content);
        $this->assertEquals('SEO Title 3', $pageLang->seo_title);
        $this->assertEquals('SEO Description 3', $pageLang->seo_description);
        $this->assertEquals('handle-3', $pageLang->{'nice-name'});
    }

    public function test_delete() {
        $pageService = new PageService(666);
        $page = $pageService->save(true,[
            'en_US' => new PageLangDTO(
                    'Name US', 'Content US', 'SEO Title US', 'SEO Description US', 'handle-us')
        ]);
        $this->DELETE("/666/pages/$page->id");

        $page = Page::find($page->id);
        $this->assertNotNull($page->deleted_at);
    }

}
