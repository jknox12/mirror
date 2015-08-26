<?php

class Pages_Adapter{

    public function format($page){
        if (is_array($page)){
            return array_map([$this, 'format_single'], $page);
        } else {
            return $this->format_single($page);
        }
    }
    
    private function format_single($page){
        $object = [
            'id' => $page->id,
            'store_id' => $page->store_id,
            'published' => $page->publish ? true : false,
            'created_at' => $page->created_at->format(DateTime::ISO8601),
            'updated_at' => $page->updated_at->format(DateTime::ISO8601),
        ];

        $i18n_map = [
            'title' => 'name',
            'nice-name' => 'handle',
            'content' => 'content',
            'seo_title' => 'seo_title',
            'seo_description' => 'seo_description',
        ];

        //Make sure the i18n relationship is loaded
        if (!isset($page->ignore['i18n'])){
            $page->i18n;
        }

        foreach ($i18n_map as $field => $new_field){
            $object[$new_field] = [];

            //Using the cached version to avoid extra queries
            foreach ($page->ignore['i18n'] as $i18n){
                $object[$new_field][$i18n->lang] = $i18n->$field;
            }
        }

        return $object;
    }
}