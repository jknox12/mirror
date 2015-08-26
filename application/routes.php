<?php

return array(
	'GET /(:num)/pages' => ['uses' => 'pages@page_list'],
	'GET /(:num)/pages/(:num)' => ['before' => 'page_exists', 'uses' => 'pages@page'],
	'POST /(:num)/pages' => ['uses' => 'pages@page'],
	'PUT /(:num)/pages/(:num)' => ['before' => 'page_exists', 'uses' => 'pages@page'],
	'DELETE /(:num)/pages/(:num)' => ['before' => 'page_exists', 'uses' => 'pages@page'],
	'GET /(:num)/pages/handle' => ['uses' => 'pages@page_handle'],
);
