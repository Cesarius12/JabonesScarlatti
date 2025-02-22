<?php
session_start();
require ('config.php');

if (!isset($_SESSION['nombre'])) {
    header('Location:index.php');
}
echo "<link rel='stylesheet' href='styles.css'>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pedidoID = $_POST['pedidos'];
    foreach ($pedidoID as $pedido) {
        $con = conectar();
        $stmt = $con->prepare('UPDATE pedidos SET entregado = 1 WHERE pedidoID = :pedidoID');
        $stmt->bindParam(':pedidoID', $pedido, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo 'Pedido '.$pedido.'marcado como entregado.';
        } else {
            echo 'Error al actualizar el estado.';
        }
    }
    echo "<form action='admin.php' method='post'>
        <input type='submit' value='Volver'>
        </form>";
} else {
    $ped = obtenerPedidos();
    mostrarPed($ped);
}

?>
