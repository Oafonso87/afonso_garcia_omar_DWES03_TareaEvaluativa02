<?php

require '../app/models/Usuario.php';

class Usuarios{

    private $usuarioModelo;

    public function __construct(){
        // Inicializamos el modelo
        $this->usuarioModelo = new Usuario();
    }
    
    //GET
    function getAllUsuarios(){         
        $usuarios = $this->usuarioModelo->getAllUsuarios();        
        
        if (empty($usuarios)) {
            // No hay usuarios que devolver
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'mensaje' => 'No hay usuarios disponibles'                
            ]);
        } else {
            // Hay usuarios, devolverlos en JSON
            http_response_code(200); // Código HTTP 200: OK
            echo json_encode([
                'status' => 200,
                'mensaje' => 'Estos son todos los usuarios que hay:',                
                'datos' => $usuarios 
            ], JSON_PRETTY_PRINT);             
        }
    }

    //GET
    function getusuarioById($id){
        $usuario = $this->usuarioModelo->getUsuarioById($id);

        if ($usuario) {
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'mensaje' => 'Este es el usuario con el Id seleccionado:',                
                'datos' => $usuario                 
            ], JSON_PRETTY_PRINT);            
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'error' => 'usuario no encontrado'                
            ]);
        }        
    }

    //POST
    public function createUsuario($data){
        // Validar el usuario según el modelo
        if (!$this->usuarioModelo->validarUsuario($data)) {
            http_response_code(400);
            echo json_encode([
                'status' => 400,
                'mensaje' => 'Error al crear el usuario. Los datos proporcionados no son validos.',
            ], JSON_PRETTY_PRINT);
            return;
        }

        $nuevoUsuario = $this->usuarioModelo->createUsuario($data);
        
        if ($nuevoUsuario) {
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'mensaje' => 'Se ha aniadido un usuario con los siguientes datos:',                
                'datos' => $nuevoUsuario                
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
    public function updateUsuario($id, $data){
        // Verificar si el usuario existe antes de intentar actualizarlo
        $usuarioExistente = $this->usuarioModelo->getUsuarioById($id);

        if (!$usuarioExistente) {
            // Usuario no encontrado
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'mensaje' => 'Usuario no encontrado.'
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Intentar actualizar el usuario
        $usuarioActualizado = $this->usuarioModelo->updateUsuario($id, $data);

        if ($usuarioActualizado) {
            // Actualización exitosa
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'mensaje' => 'Usuario actualizado con exito, estos son sus datos:',
                'datos' => $usuarioActualizado
            ], JSON_PRETTY_PRINT);
        } else {
            // Error en la actualización (por ejemplo, datos inválidos)
            http_response_code(400);
            echo json_encode([
                'status' => 400,
                'mensaje' => 'Error al actualizar el usuario. Verifique los datos proporcionados.'
            ], JSON_PRETTY_PRINT);
        }
    }

    //DELETE
    public function deleteUsuario($id){
        if ($this->usuarioModelo->deleteUsuario($id)) {
            http_response_code(200);
            echo json_encode([
                'status' => 204,
                'mensaje' => "Usuario con ID $id eliminado con exito."                
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'mensaje' => 'Usuario no encontrado para eliminar.'                
            ], JSON_PRETTY_PRINT);
        }        
    }
}