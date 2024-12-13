<?php

class Producto{    

    private $datos;
    private $archivo;

    public function __construct() {
        // Cargar y decodificar el archivo JSON        
        $this->archivo = __DIR__ . '/../../bbdd/productos.json';
        if (file_exists($this->archivo)) {
            $jsonContenido = file_get_contents($this->archivo); // Se Obtiene el contenido del archivo
            $this->datos = json_decode($jsonContenido, true); // Se convierte el JSON en un array asociativo
        } else {
            $this->datos = [];
        }
    }

    // Método para obtener todos los productos
    public function getAllProductos() {
        return $this->datos;
    }

    // Método para obtener un producto por su ID
    public function getProductoById($id) {
        foreach ($this->datos as $producto) {
            if ($producto['id'] == $id) {
                return $producto; // Retorna el producto si coincide el ID
            }
        }
        return null;
    }

    // Validar producto
    public function validarProducto($producto) {        
        $clavesObligatorias = ['nombre', 'marca', 'precio', 'unidades'];

        // Comprobar si todas las claves obligatorias están presentes y no están vacías
        foreach ($clavesObligatorias as $clave) {
            if (!isset($producto[$clave]) || empty($producto[$clave])) {
                return false;
            }
        }
        return true;
    }

    // Método para crear un nuevo producto
    public function createProducto($nuevoProducto){
        $productos = $this->datos;            

        // Asignamos un nuevo ID al producto y lo ponemos al principio del array
        $nuevoId = end($productos)['id'] + 1;
        $nuevoProducto = array_merge(['id' => $nuevoId], $nuevoProducto);

        // Añadimos el nuevo producto al array existente
        $productos[] = $nuevoProducto;        

        // Guardamos los datos actualizados en el archivo JSON
        file_put_contents($this->archivo, json_encode($productos, JSON_PRETTY_PRINT));

        return $nuevoProducto;        
    }

    // Método para actualizar un producto
    public function updateProducto($id, $datosActualizados){
        $productos = $this->datos;

        foreach ($productos as &$producto) {
            if ($producto['id'] == $id) {
                // Actualizamos los datos del producto
                $producto = array_merge($producto, $datosActualizados);
                file_put_contents($this->archivo, json_encode($productos, JSON_PRETTY_PRINT));
                return $producto;
            }
        }
        return null;        
    }

    // Método para eliminar un producto
    public function deleteProducto($id){           
        $productos = $this->datos;
        
        // Buscar si el ID del producto existe
        $productoIndice = null;
        foreach ($productos as $indice => $producto) {
            if ($producto['id'] == $id) {
                $productoIndice = $indice;
                break;
            }
        }
        
        // Si no se encontró el usuario con el ID, devolver false
        if ($productoIndice === null) {
            return false;
        }

        // Eliminar el producto
        array_splice($productos, $productoIndice, 1);
        
        // Guardar el archivo JSON actualizado
        file_put_contents($this->archivo, json_encode($productos, JSON_PRETTY_PRINT));

        return true;
    }
}