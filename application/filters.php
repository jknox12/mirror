<?php

return array(

	'before' => function(){
	    ini_set('html_errors', 'off');
	},

	'after' => function($response){
        if ($response->status == 200){
            $response->header('cache-control', 'public, max-age=1800'); // 30 minutes
        } else {
            $response->header('cache-control', 'no-cache');
        }
	},

	// Tag a response as a page for cache invalidations
	'tag_page' => function($response){
        $response->header('X-Object-Type', 'page');

        // Tag store id as well
        $parts = explode('/', Request::uri());
		if (isset($parts[0]) && is_numeric($parts[0])){
			$response->header('X-Object-Store', $parts[0]);
		}
	},

    // Returns 404 if the resource doesn't exist
    'page_exists' => function() {
		$parameters = Request::route()->parameters;

        $store_id = $parameters[0];
        $id = $parameters[1];
        $page = Page::find($id);

        if ($page == null || (isset($page->deleted_at) && $page->deleted_at != null) || $page->store_id != $store_id){
            return Response::json(['msg' => "Page not found"], 404);
        }
    },

);
