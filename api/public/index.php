<?php

require '../core/Router.php';
require '../app/controllers/Productos.php';
require '../app/controllers/Usuarios.php';

$url = $_SERVER['QUERY_STRING'];

$router = new Router();

$router->add('/tienda', array(
    'controller'=>'Home',
    'action'=>'index'
));

$router->add('/tienda/productos/get', array(
    'controller'=>'Productos',
    'action'=>'getAllProductos'
));

$router->add('/tienda/productos/get/{id}', array(
    'controller'=>'Productos',
    'action'=>'getProductoById'
));

$router->add('/tienda/productos/create', array(
    'controller'=>'Productos',
    'action'=>'createProducto'
));

$router->add('/tienda/productos/update/{id}', array(
    'controller'=>'Productos',
    'action'=>'updateProducto'
));

$router->add('/tienda/productos/delete/{id}', array(
    'controller'=>'Productos',
    'action'=>'deleteProducto'
));

$router->add('/tienda/usuarios/get', array(
    'controller'=>'Usuarios',
    'action'=>'getAllUsuarios'
));

$router->add('/tienda/usuarios/get/{id}', array(
    'controller'=>'Usuarios',
    'action'=>'getUsuarioById'
));

$router->add('/tienda/usuarios/create', array(
    'controller'=>'Usuarios',
    'action'=>'createUsuario'
));

$router->add('/tienda/usuarios/update/{id}', array(
    'controller'=>'Usuarios',
    'action'=>'updateUsuario'
));

$router->add('/tienda/usuarios/delete/{id}', array(
    'controller'=>'Usuarios',
    'action'=>'deleteUsuario'
));

$urlParams = explode('/', $url);

$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>'',
    'action'=>'',
    'params'=>''
);

if(!empty($urlParams[2])){
    $urlArray['controller'] = ucwords($urlParams[2]);
    if(!empty($urlParams[3])){
        $urlArray['action'] = $urlParams[3];
        if(!empty($urlParams[4])){
            $urlArray['params'] = $urlParams[4];
        }
    }else {
        $urlArray['action']='index';
    }
}else {
    $urlArray['controller']='Home';
    $urlArray['action']='index';
}

if ($router->matchRoutes($urlArray)){

    $method = $_SERVER['REQUEST_METHOD'];

    $params = [];

    if($method === 'GET') {

        $params[]=intval($urlArray['params']) ?? null;

    }elseif ($method === 'POST') {

        $json = file_get_contents('php://input');
        $params[]=json_decode($json,true);

    }elseif ($method === 'PUT') {

        $id =intval($urlArray['params']) ?? null;
        $json = file_get_contents('php://input');
        $params[]=$id;
        $params[]=json_decode($json,true);
        
    }elseif ($method === 'DELETE') {
        
        $params[]=intval($urlArray['params']) ?? null;

    }

    $controller= $router->getParams()['controller'];
    $action= $router->getParams()['action'];
    $controller = new $controller();

    if(method_exists($controller, $action)){
        $resp = call_user_func_array([$controller, $action], $params);
    }else{
        echo "El metodo no existe";
    }
    }else{
        http_response_code(404);
        echo "No route found for URL '$url'";
}

?>