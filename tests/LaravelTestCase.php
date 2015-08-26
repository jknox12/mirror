<?php

abstract class LaravelTestCase extends TiendaNubeTestCase {

    public function GET($URI, $data = []) {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $URI;
        $_SERVER['CONTENT_TYPE']= 'application/x-www-form-urlencoded';
        $_POST = $data;
        return json_decode($this->doRequest($URI, $data));
    }

    public function POST($URI, $data = []) {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = $URI;
        $_SERVER['CONTENT_TYPE']= 'application/x-www-form-urlencoded';
        $_POST = $data;
        return json_decode($this->doRequest($URI, $data));
    }

    public function PUT($URI, $data = []) {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['REQUEST_URI'] = $URI;
        $_SERVER['CONTENT_TYPE']= 'application/x-www-form-urlencoded';
        $_POST = null;
        return json_decode($this->doRequest($URI, $data));
    }

    public function DELETE($URI, $data = []) {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['REQUEST_URI'] = $URI;
        $_SERVER['CONTENT_TYPE']= 'application/x-www-form-urlencoded';
        $_POST = null;
        return json_decode($this->doRequest($URI, $data));
    }

    private function doRequest($URI, $data = []) {
        Input::$input = $data;

        Laravel\Routing\Filter::register(require APP_PATH.'filters'.EXT);
        $loader = new Laravel\Routing\Loader(APP_PATH, ROUTE_PATH);
        $router = new Laravel\Routing\Router($loader, CONTROLLER_PATH);

        IoC::instance('laravel.routing.router', $router);

        Laravel\Request::$route = $router->route(
            Laravel\Request::method(), Laravel\URI::current());

        if ( ! is_null(Request::$route)) {
            $response = Request::$route->call();
        } else {
            Laravel\Routing\Filter::run(['before'], [], true);
            $response = Laravel\Response::json(['msg' => 'Not found'], 404);
            \Laravel\Routing\Filter::run(['after'], [$response], true);
        }

        ob_start();
        $response->send();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}
