<?php

require '../app/models/Producto.php';

class Productos{

    private $productoModelo;

    public function __construct(){
        // Inicializamos el modelo
        $this->productoModelo = new Producto();
    }
    
    //GET
    function getAllProductos(){         
        $productos = $this->productoModelo->getAllProductos();        
        
        if (empty($productos)) {
            // No hay productos que devolver
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'mensaje' => 'No hay productos disponibles'                
            ]);
        } else {
            // Hay productos, devolverlos en JSON
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'mensaje' => 'Estos son todos los productos que hay:',                
                'datos' => $productos 
            ], JSON_PRETTY_PRINT);             
        }
    }

    //GET
    function getProductoById($id){
        $producto = $this->productoModelo->getProductoById($id);

        if ($producto) {
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'mensaje' => 'Este es el producto con el Id seleccionado:',                
                'datos' => $producto                 
            ], JSON_PRETTY_PRINT);            
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'error' => 'Producto no encontrado'                
            ]);
        }        
    }

    //POST
    public function createProducto($data){
        // Validar el producto según el modelo
        if (!$this->productoModelo->validarProducto($data)) {
            http_response_code(400);
            echo json_encode([
                'status' => 400,
                'mensaje' => 'Error al crear el producto. Los datos proporcionados no son validos.',
            ], JSON_PRETTY_PRINT);
            return;
        }

        $nuevoProducto = $this->productoModelo->createProducto($data);
        
        if ($nuevoProducto) {
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'mensaje' => 'Se ha aniadido un producto con los siguientes datos:',                
                'datos' => $nuevoProducto                
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 500,
                'mensaje' => 'Error interno al guardar el producto.'                
            ]);
        }        
    }

    //PUT
    public function updateProducto($id, $data){        
    // Verificar si el producto existe antes de intentar actualizarlo
        $productoExistente = $this->productoModelo->getProductoById($id);

        if (!$productoExistente) {
            // Producto no encontrado
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'mensaje' => 'Producto no encontrado.'
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Intentar actualizar el producto
        $productoActualizado = $this->productoModelo->updateProducto($id, $data);

        if ($productoActualizado) {
            // Actualización exitosa
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'mensaje' => 'Producto actualizado con exito, estos son sus datos:',
                'datos' => $productoActualizado
            ], JSON_PRETTY_PRINT);
        } else {
            // Error en la actualización (por ejemplo, datos inválidos)
            http_response_code(400);
            echo json_encode([
                'status' => 400,
                'mensaje' => 'Error al actualizar el producto. Verifique los datos proporcionados.'
            ], JSON_PRETTY_PRINT);
        }
    }

    //DELETE
    public function deleteProducto($id){
        if ($this->productoModelo->deleteProducto($id)) {
            http_response_code(200);
            echo json_encode([
                'status' => 204,
                'mensaje' => "Producto con ID $id eliminado con exito."                
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'mensaje' => 'Producto no encontrado para eliminar.'                
            ], JSON_PRETTY_PRINT);
        }        
    }
}