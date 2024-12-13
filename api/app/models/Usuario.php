<?php

class Usuario{

    private $datos;
    private $archivo;

    public function __construct(){        
        // Cargar y decodificar el archivo JSON        
        $this->archivo = __DIR__ . '/../../bbdd/usuarios.json';
        if (file_exists($this->archivo)) {
            $jsonContenido = file_get_contents($this->archivo); // Se Obtiene el contenido del archivo
            $this->datos = json_decode($jsonContenido, true); // Se convierte el JSON en un array asociativo
        } else {
            $this->datos = [];
        }
    }

    // Método para obtener todos los usuarios
    public function getAllUsuarios(){
        return $this->datos;
    }

    // Método para obtener un usuario por su ID
    public function getUsuarioById($id){
        foreach ($this->datos as $usuario) {
            if ($usuario['id'] == $id) {
                return $usuario; // Retorna el usuario si coincide el ID
            }
        }
        return null;
    }

    // Validar usuario
    public function validarUsuario($usuario) {        
        $clavesObligatorias = ['nombre', 'apellidos', 'email', 'password'];

        // Comprobar si todas las claves obligatorias están presentes y no están vacías
        foreach ($clavesObligatorias as $clave) {
            if (!isset($usuario[$clave]) || empty($usuario[$clave])) {
                return false;
            }
        }
        return true;
    }

    // Método para crear un nuevo usuario
    public function createUsuario($nuevoUsuario){
        $usuarios = $this->datos;

        // Asignamos un nuevo ID al usuario y lo ponemos al principio del array
        $nuevoId = end($usuarios)['id'] + 1;
        $nuevoUsuario = array_merge(['id' => $nuevoId], $nuevoUsuario);

        // Añadimos el nuevo usuario al array existente
        $usuarios[] = $nuevoUsuario;

        // Guardamos los datos actualizados en el archivo JSON
        file_put_contents($this->archivo, json_encode($usuarios, JSON_PRETTY_PRINT));

        return $nuevoUsuario;
    }

    // Método para actualizar un usuario
    public function updateUsuario($id, $datosActualizados){
        $usuarios = $this->datos;

        foreach ($usuarios as &$usuario) {
            if ($usuario['id'] == $id) {
                // Actualizamos los datos del usuario
                $usuario = array_merge($usuario, $datosActualizados);
                file_put_contents($this->archivo, json_encode($usuarios, JSON_PRETTY_PRINT));
                return $usuario;
            }
        }
        return null;
    }

    // Método para eliminar un usuario
    public function deleteUsuario($id){          
        $usuarios = $this->datos;
        
        // Buscar si el ID del usuario existe
        $usuarioIndice = null;
        foreach ($usuarios as $indice => $usuario) {
            if ($usuario['id'] == $id) {
                $usuarioIndice = $indice;
                break;
            }
        }

        // Si no se encontró el usuario con el ID, devolver false
        if ($usuarioIndice === null) {
            return false;
        }

        // Eliminar el usuario
        array_splice($usuarios, $usuarioIndice, 1);
        
        // Guardar el archivo JSON actualizado
        file_put_contents($this->archivo, json_encode($usuarios, JSON_PRETTY_PRINT));

        return true;
    }
}