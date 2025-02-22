<?php
session_start();  // Iniciar sesión
require ('config.php');

// Mostrar botón de cerrar sesión si hay usuario logueado
if (isset($_SESSION['nombre'])) {
    echo "<div style='position: absolute; top: 10px; right: 10px;'>
              <a href='logout.php' style='padding: 10px; background-color: red; color: white; text-decoration: none;'>Cerrar sesión</a>
          </div>";
}
echo "<link rel='stylesheet' href='styles.css'>";
// Verificar si el formulario fue enviado con todos los datos requeridos
if (empty($_POST['nombre']) || empty($_POST['descrip']) || empty($_POST['peso']) || empty($_POST['precio'])) {
    echo "<form action='insertar.php' method='post' enctype='multipart/form-data'>";
    echo "Nombre: <input type='text' name='nombre'><br>";
    echo "Descripcion: <input type='text' name='descrip'><br>";
    echo "Peso: <input type='number' min='0' name='peso'><br>";
    echo "Precio: <input type='number' min='0' name='precio'><br>";
    echo "Imagen: <input type='file' name='img' accept='image/*'><br>";
    echo "<input type='submit' value='Insertar Producto'>";
    echo '</form>';
} else {
    $nombre = ($_POST['nombre']);
    $descri = ($_POST['descrip']);
    $peso = ($_POST['peso']);
    $precio = ($_POST['precio']);

    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['img']['tmp_name'];
        $nom = $_FILES['img']['name'];
        $ruta = '/var/www/html/2doTrim/JabonesScarlatti/imagenes/';
        $ext = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
        $extensiones = ['jpg', 'jpeg', 'png', 'gif'];

        if (!empty($_SESSION['idprod'])) {
            $id = end($_SESSION['idprod']);
        }

        if (in_array($ext, $extensiones)) {
            $nomImg = "Jabon$id.$ext";
            $img = $ruta . $nomImg;
            move_uploaded_file($tmp, $img);
        }else{
            $nomImg=null;
        }
    }
    añadirProd($nombre, $descri, $peso, $precio, $nomImg);
}
?>
