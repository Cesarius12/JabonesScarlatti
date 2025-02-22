<?php
ini_set('display_errors', 1);  // Muestra los errores
ini_set('display_startup_errors', 1);  // Muestra errores al inicio
error_reporting(E_ALL);  // Reporta todos los errores

require ('config.php');
session_start();
if (!isset($_SESSION['nombre'])) {
    header('Location:index.php');
}
echo "<link rel='stylesheet' href='styles.css'>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['productos']) && isset($_POST['cantidad'])) {
        $productosSeleccionados = $_POST['productos'];
        $cantidadesSeleccionadas = $_POST['cantidad'];
        $cantidadTotal = 0;
        foreach ($cantidadesSeleccionadas as $cantidad) {
            $cantidadTotal += (int) $cantidad;
        }
        $_SESSION['id'] = array();
        if ($cantidadTotal <= 2) {
            $productosConPrecios = array();

            foreach ($productosSeleccionados as $producto) {
                list($nombre, $precio, $id) = explode(',', $producto);
                $productosConPrecios[] = array('nombre' => $nombre, 'precio' => $precio, 'cantidad' => $cantidadesSeleccionadas[$id], 'id' => $id);
                $_SESSION['id'][] = $id;
            }

            setcookie('productos', serialize($productosConPrecios), time() + (86400 * 30), '/');  // Expira en 30 días
            foreach ($productosConPrecios as $producto) {
                $_SESSION['cant'][] = $producto;
                if ($_SESSION['nombre'] != 'invitado') {
                    compruebaPedido($_SESSION['email'], $producto['cantidad'], $producto['id']);
                }
            }
            mostrarProdComp($productosConPrecios);
        } else {
            echo 'No puedes seleccionar más de 2 productos en total (contando las cantidades). <br><br>';
            echo "<form action='catalogo.php' method='post'>";
            echo "    <button type='submit'>Pulse para volver</button>";
            echo '</form>';
        }
    } else {
        echo 'No seleccionaste productos o cantidades. <br><br>';
        echo "<form action='catalogo.php' method='post'>";
        echo "    <button type='submit'>Pulse para volver al catálogo</button>";
        echo '</form>';
    }
} else {
    if (isset($_SESSION['productos']) && !empty($_SESSION['productos'])) {
        $prod = $_SESSION['productos'];
        compruebaPedido($_SESSION['email'], $producto['cantidad'], $producto['id']);
        mostrarProdComp($prod);
    } else {
        echo 'No seleccionaste productos. <br><br>';
        echo "<form action='catalogo.php' method='post'>";
        echo "    <button type='submit'>Pulse para volver al catálogo</button>";
        echo '</form>';
    }
}

?>

