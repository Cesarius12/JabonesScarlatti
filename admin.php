<?php
session_start();  // Asegúrate de que la sesión esté iniciada
require ('config.php');

$cat = '';

// Verifica si la sesión está iniciada y si hay un usuario logueado
if (isset($_SESSION['nombre'])) {
    $cat .= "<div style='position: absolute; top: 10px; right: 10px;'>
                 <a href='logout.php' style='padding: 10px; background-color: red; color: white; text-decoration: none;'>Cerrar sesión</a>
             </div>";
}

if (isset($_POST['opcion']) && $_POST['opcion'] !== '') {
    $opcion = $_POST['opcion'];

    switch ($opcion) {
        case '1':
            header('Location:insertar.php');
            exit;
        case '3':
            header('Location:elim.php');
            exit;
        case '4':
            header('Location:gestionaPedido.php');
            exit;
    }
} else {
    echo "<html lang='es'>";
    echo '<head>';
    echo "    <meta charset='UTF-8'>";
    echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo '    <title>Administrar Productos</title>';
    echo "<link rel='stylesheet' href='styles.css'>";
    echo '</head>';
    echo '<body>';
    echo $cat;
    echo '    <h2>Administracion</h2>';
    echo "    <form action='admin.php' method='post'>";
    echo "        <label for='opcion'>Selecciona una acción:</label>";
    echo "        <select name='opcion'>";
    echo "            <option value=''>-- Elige una opción --</option>";
    echo "            <option value='1'>Añadir Producto</option>";
    echo "            <option value='3'>Eliminar Producto</option>";
    echo "            <option value='4'>Gestionar Pedidos</option>";
    echo '        </select>';
    echo "        <input type='submit' value='Aceptar'>";
    echo '    </form>';
    echo '</body>';
    echo '</html>';
}
?>
