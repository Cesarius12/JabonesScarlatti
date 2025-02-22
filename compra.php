<?php
require("config.php");
require("pdf.php");
session_start();
if(!isset($_SESSION['nombre'])){
    header("Location:index.php");
}
spl_autoload_register(function ($clase) {
    $fullpath = "./PHPMailer-master/src/" . $clase . ".php";
    if (file_exists($fullpath))
        require_once $fullpath;
    else
        echo "<p>La clase $fullpath no se encuentra</p>";
});
echo "<link rel='stylesheet' href='styles.css'>";
$mail = new PHPMailer(true);
// Configuración del servidor
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                // Habilita la salida de depuración detallada
$mail->isSMTP();                                      // Establece el tipo de correo electrónico para usar SMTP
$mail->Host = 'localhost';                             // Especifica los servidores SMTP principales y de respaldo
$mail->SMTPAuth = false;                               // Habilita la autenticación SMTP
$mail->Username = 'cesar';                             // Nombre de usuario SMTP
$mail->Password = 'cesar';                             // Contraseña SMTP
$mail->Port = 587;                                      // Puerto TCP para conectarse
// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Habilita el cifrado TLS; `ssl` también aceptado


$email= $_SESSION['email'];// Correos seleccionados
$productos="";
if($_COOKIE['productos']){
$productos=unserialize($_COOKIE['productos']);
}elseif($_SESSION['productos']){
$productos=$_SESSION['productos'];
}

$total = 0;
foreach ($productos as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];  // Acumulamos el precio de los productos
}
// Generar el archivo PDF
$nombrePDF = generarPDF($_SESSION['nombre'], $productos, $total);


// Guardar el archivo PDF en el servidor
// Asegúrate de que la carpeta donde se guarda el PDF tenga permisos de escritura


if (empty($email)) {
    echo "No se han seleccionado emails.";
    exit;
}
// Preparamos los datos para la tabla pedidos
$email = $_SESSION['email'];
$fechaPedido = date('Y-m-d H:i:s'); // Fecha actual
$fechaEntrega = date('Y-m-d', strtotime('+5 days')); // Fecha de entrega en 5 días
$estadoPedido = 0; // Estado inicial
$cant=$_SESSION['cant'];
$ids=$_SESSION['id'];

// Iniciar transacción para insertar ambos registros
try {
    $conexion = conectar();
    // Iniciamos la transacción
    $conexion->beginTransaction();

    // Insertar en la tabla pedidos
    $query = "INSERT INTO pedidos (email, fechaPedido, fechaEntrega, totalPedido, entregado) 
              VALUES (:email, :fechaPedido, :fechaEntrega, :totalPedido, :estadoPedido)";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fechaPedido', $fechaPedido);
    $stmt->bindParam(':fechaEntrega', $fechaEntrega);
    $stmt->bindParam(':totalPedido', $total);
    $stmt->bindParam(':estadoPedido', $estadoPedido);
    $stmt->execute();

    // Obtener el ID del pedido insertado
    $pedidoID = $conexion->lastInsertId();

    // Insertar en la tabla itempedido
    for ($i = 0; $i < count($productos); $i++) {
        $queryItem = "INSERT INTO itempedido (pedidoID, productoID, unidades) 
                      VALUES (:pedidoID, :productoID, :unidades)";
        $stmtItem = $conexion->prepare($queryItem);
        $stmtItem->bindParam(':pedidoID', $pedidoID);
        $stmtItem->bindParam(':productoID', $ids[$i]);  // Asumiendo que cada producto tiene un id
        $stmtItem->bindParam(':unidades', $cant[$i]['cantidad']);  // Número de unidades
        $stmtItem->execute();
    }

    // Si todo sale bien, hacer commit
    $conexion->commit();

    
    echo "<h3>Resumen de la compra</h3>";
    echo "<p><strong>Total del pedido: </strong> $ " . number_format($total, 2) . "</p>";
    echo "<h4>Artículos pedidos:</h4><ul>";
    foreach ($productos as $producto) {
        echo "<li>" . $producto['nombre'] . " - Cantidad: " . $producto['cantidad'] . " - Precio: $" . number_format($producto['precio'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Fecha estimada de entrega: </strong>" . $fechaEntrega . "</p>";

    // Enviar correo, etc...
    $mail->addAttachment($nombrePDF);
    $mail->setFrom('cesar@cesarbarba.blog', 'Cesar');
    $mail->Subject = 'Compra realizada a: ' . date('Y-m-d H:i:s');
    $mail->Body = "Mensaje: Señor " . $_SESSION['nombre'] . " su compra es " . mostrarComp($productos);

    // Enviar correo
    $mail->addAddress($email);
    if ($mail->send()) {
        echo "Mail a $email efectuado con éxito.<br>";
    } else {
        echo "Mail a $email no pudo ser enviado.<br>";
    }

    echo '<br><br>';
    echo "<div style='text-align: center; margin-top: 10px;'>";
    unlink($nombrePDF);  // Eliminar el archivo PDF del servidor
    echo "<a href='logout.php' style='text-decoration: none;'><button type='button'>Salir</button></a>";
    echo "</div>";
} catch (Exception $e) {
    // Si algo falla, hacer rollback
    $conexion->rollBack();
    echo "Error al realizar el pedido: " . $e->getMessage();
}


function mostrarComp($prod){
    $texto="";
    foreach ($prod as $producto) {
        $texto.= $producto['cantidad']." unidades de  ". $producto['nombre'] ." a ". $producto['precio'];
    }
    return $texto;
}
?>
