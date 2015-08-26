<?php

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RoutesResponseTest extends LaravelTestCase {

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

        $this->assertEquals(1, $response->id);
        $this->assertEquals(666, $response->store_id);
        $this->assertTrue($response->published);
        $this->assertNotNull($response->created_at);
        $this->assertNotNull($response->updated_at);

        $this->assertEquals('Name', $response->name->en_US);
        $this->assertEquals('Nombre', $response->name->es_AR);
        $this->assertEquals('Nome', $response->name->pt_BR);

        $this->assertEquals('Content', $response->content->en_US);
        $this->assertEquals('Contenido', $response->content->es_AR);
        $this->assertEquals('Conteúdo', $response->content->pt_BR);

        $this->assertEquals('SEO Title', $response->seo_title->en_US);
        $this->assertEquals('Título de SEO', $response->seo_title->es_AR);
        $this->assertEquals('Título para SEO', $response->seo_title->pt_BR);

        $this->assertEquals('SEO Description', $response->seo_description->en_US);
        $this->assertEquals('Descripción de SEO', $response->seo_description->es_AR);
        $this->assertEquals('Descrição para SEO', $response->seo_description->pt_BR);

        $this->assertEquals('url-us', $response->handle->en_US);
        $this->assertEquals('url-ar', $response->handle->es_AR);
        $this->assertEquals('url-br', $response->handle->pt_BR);
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
        $response = $this->PUT("/666/pages/$page->id", [
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

        $this->assertEquals($page->id, $response->id);
        $this->assertEquals(666, $response->store_id);
        $this->assertFalse($response->published);
        $this->assertNotNull($response->created_at);
        $this->assertNotNull($response->updated_at);

        $this->assertEquals('Name 1', $response->name->en_US);
        $this->assertEquals('Name 2', $response->name->es_AR);
        $this->assertEquals('Name 3', $response->name->pt_BR);

        $this->assertEquals('Content 1', $response->content->en_US);
        $this->assertEquals('Content 2', $response->content->es_AR);
        $this->assertEquals('Content 3', $response->content->pt_BR);

        $this->assertEquals('SEO Title 1', $response->seo_title->en_US);
        $this->assertEquals('SEO Title 2', $response->seo_title->es_AR);
        $this->assertEquals('SEO Title 3', $response->seo_title->pt_BR);

        $this->assertEquals('SEO Description 1', $response->seo_description->en_US);
        $this->assertEquals('SEO Description 2', $response->seo_description->es_AR);
        $this->assertEquals('SEO Description 3', $response->seo_description->pt_BR);

        $this->assertEquals('handle-1', $response->handle->en_US);
        $this->assertEquals('handle-2', $response->handle->es_AR);
        $this->assertEquals('handle-3', $response->handle->pt_BR);
    }

    public function test_delete() {
        $pageService = new PageService(666);
        $page = $pageService->save(true,[
            'en_US' => new PageLangDTO(
                    'Name US', 'Content US', 'SEO Title US', 'SEO Description US', 'handle-us')
        ]);
        $response = $this->DELETE("/666/pages/$page->id");
        $this->assertEmpty($response);
    }

    public function test_get() {
        $pageService = new PageService(666);
        $page = $pageService->save(true,[
            'en_US' => new PageLangDTO(
                    'Name US', 'Content US', 'SEO Title US', 'SEO Description US', 'handle-us')
        ]);
        $response = $this->GET("/666/pages/$page->id");

        $this->assertEquals($page->id, $response->id);
        $this->assertEquals(666, $response->store_id);
        $this->assertTrue($response->published);
        $this->assertNotNull($response->created_at);
        $this->assertNotNull($response->updated_at);
        $this->assertEquals('Name US', $response->name->en_US);
        $this->assertEquals('Content US', $response->content->en_US);
        $this->assertEquals('SEO Title US', $response->seo_title->en_US);
        $this->assertEquals('SEO Description US', $response->seo_description->en_US);
        $this->assertEquals('handle-us', $response->handle->en_US);
    }

}
