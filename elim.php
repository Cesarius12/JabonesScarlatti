<?php
session_start();  // Asegúrate de que la sesión esté iniciada
require ('config.php');
echo "<link rel='stylesheet' href='styles.css'>";
// Verifica si la sesión está iniciada y si hay un usuario logueado
if (isset($_SESSION['nombre'])) {
    $cat .= "<div style='position: absolute; top: 10px; right: 10px;'>
                 <a href='logout.php' style='padding: 10px; background-color: red; color: white; text-decoration: none;'>Cerrar sesión</a>
             </div>";
}
if(isset($_POST['elim'])){
    $id=$_POST['id'];
    eliminarPorID($id);
}else{
echo "    <form method='post' action='elim.php'>";
echo "        <label>ID del Producto a Eliminar:</label>";
echo "        <select name='id'>";
foreach($_SESSION['idprod'] as $valor){
    echo "            <option value='$valor'> $valor </option>";
}
echo "        </select>";
echo "        <button type='submit' name='elim'>Eliminar Producto</button>";
echo "    </form>";
echo $cat;
}
?>