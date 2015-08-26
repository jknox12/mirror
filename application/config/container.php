<?php

return [
	'controllers.pages' => array('resolver' => function(){
	    return new Pages_Controller(new Pages_Adapter);
	}),
];
