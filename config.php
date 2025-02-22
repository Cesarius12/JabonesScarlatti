<?php
ini_set('display_errors', 1);  // Muestra los errores
ini_set('display_startup_errors', 1);  // Muestra errores al inicio
error_reporting(E_ALL);  // Reporta todos los errores
echo "<link rel='stylesheet' href='styles.css'>";
function conectar()
{
    try {
        $con = new PDO('mysql:host=127.0.0.1;dbname=TiendaOnline', 'root', 'cesar');
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
function obtenerUsers()
{
    $conexion = conectar();

    $consulta = $conexion->query('SELECT u.*, c.nombre, c.email
        FROM usuarios u
        INNER JOIN clientes c ON u.email = c.email');
    return $consulta->fetchAll(PDO::FETCH_ASSOC);

    return $false;
}
function obtenerProductos()
{
    $con = conectar();
    $consulta = $con->query('SELECT * FROM productos');
    return $consulta->fetchAll(pdo::FETCH_ASSOC);

    return false;
}

function mostrarProd($prod)
{
    $rutaimg = './imagenes/';  // Directorio de las imágenes
    echo "<form method='POST' action='procesaCompra.php'>";
    echo "<table border='1' align='center'>";
    echo '<thead><tr><th>Jabones</th><th>Precio</th><th>imagen</th><th>compra</th></tr></thead>';

    if ($prod) {
        foreach ($prod as $valor) {
            echo '<tr>';
            echo '<td>' . ($valor['nombre']) . '</td>';
            echo '<td>' . ($valor['precio']) . '</td>';
            echo "<td><img src='" . $rutaimg . ($valor['imagen']) . "' alt='Imagen del producto' style='width:100px; height:100px;'></td>";
            // Crear checkbox con el nombre del producto
            $producto_info = $valor['nombre'] . ',' . $valor['precio'] . ',' . $valor['productoID'];
            echo "<td><input type='number' name='cantidad[" . $valor['productoID'] . "]' min='0' max='2' value='0' ></td>";
            echo "<td><input type='checkbox' name='productos[]' value='" . $producto_info . "' class='producto-checkbox'></td>";

            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='4'>No hay Jabones disponibles</td></tr>";
    }

    echo '</table>';
    echo "<input type='submit' value='Enviar'>";
    echo '</form>';
}

function mostrarProdComp($prod)
{
    echo 'Has seleccionado los siguientes productos: <br>';
    foreach ($prod as $producto) {
        echo 'Cantidad: ' . $producto['cantidad'] . ' Producto: ' . $producto['nombre'] . ' - Precio: ' . $producto['precio'] . '<br>';
    }
    if ($_SESSION['nombre'] == 'invitado') {
        echo 'Debe logearse para continuar con la compra';
        echo "<a href='index.php'><button>Desea Loggearse?</button></a>";
    } else {
        echo "Confirma que este es su email: $_SESSION[email]<br>";
        echo "<a href='compra.php'><button> Pagar </button></a>";
    }
}

function eliminarPorID($id)
{
    $conexion = conectar();
    try {
        $query = 'DELETE FROM productos WHERE productoID = :productoID';
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':productoID', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo 'Producto eliminado con éxito.';
            unset($_SESSION['idprod'][$id]);  // No funciona
            header('Refresh: 2; url=admin.php');
            exit();
        } else {
            echo 'Error al eliminar el producto.';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function añadirProd($nombre, $desc, $peso, $precio, $img)
{
    $con = conectar();
    try {
        $query = 'INSERT INTO productos(nombre, descripcion, peso, precio, imagen) VALUES (:nombre,:descr,:peso,:precio,:img)';
        $stmt = $con->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descr', $desc);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':img', $img);
        if ($stmt->execute()) {
            echo 'Producto insertado con éxito.';
            header('Refresh: 2; url=admin.php');
            exit();
        } else {
            echo 'Error al eliminar el producto.';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function obtenerPedidos()
{
    $con = conectar();
    $consulta = $con->query('SELECT * FROM pedidos');
    return $consulta->fetchAll(pdo::FETCH_ASSOC);
}

function mostrarPed($ped)
{
    echo "<form method='POST' action='gestionaPedido.php'>";
    echo "<table border='1' align='center'>";
    echo '<thead><tr><th>ID Pedido</th><th>Email Cliente</th><th>Fecha Entrega</th><th>Estado</th><th>Acción</th></tr></thead>';
    
    if ($ped) {
        foreach ($ped as $pedido) {
            echo '<tr>';
            echo '<td>' . $pedido['pedidoID'] . '</td>';
            echo '<td>' . htmlspecialchars($pedido['email']) . '</td>';
            echo '<td>' . $pedido['fechaEntrega'] . '</td>';
            if($pedido['entregado'] == '0'){
            echo '<td> No entregado </td>';
            echo "<td><input type='checkbox' name='pedidos[]' value='" . $pedido['pedidoID'] . "' class='pedido-checkbox'></td>";
            }else{
                echo '<td> Entregado </td>';
            }

            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No hay pedidos pendientes</td></tr>";
    }
    
    echo '</table>';
    echo "<input type='submit' value='Actualizar Estado'>";
    echo '</form>';
    echo "<form action='admin.php' method='post'>
    <input type='submit' value='Volver'>
    </form>";
}
function mostrarPedMod($ped)
{
    echo "<form method='POST' action='modificar.php'>";
    echo "<table border='1' align='center'>";
    echo '<thead><tr><th>ID Pedido</th><th>Email Cliente</th><th>Fecha Entrega</th><th>Estado</th><th>Acción</th></tr></thead>';
    
    if ($ped) {
        foreach ($ped as $pedido) {
            echo '<tr>';
            echo '<td>' . $pedido['pedidoID'] . '</td>';
            echo '<td>' . htmlspecialchars($pedido['email']) . '</td>';
            echo '<td>' . $pedido['fechaEntrega'] . '</td>';
            if($pedido['entregado'] == '0'){
            echo '<td> No entregado </td>';
            }else{
                echo '<td> Entregado </td>';
            }
            echo "<td><input type='radio' name='pedidos' value='" . $pedido['pedidoID'] . "' class='pedido-checkbox'></td>";
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No hay pedidos pendientes</td></tr>";
    }
    
    echo '</table>';
    echo "<input type='submit' value='Modificar'>";
    echo '</form>';
    echo "<form action='admin.php' method='post'>
    <input type='submit' value='Volver'>
    </form>";
}


function cestaID($email)
{
    $con = conectar();
    $stmt = 'SELECT cestaID FROM cesta WHERE email = :email';  // Usamos :email como marcador de parámetro
    $consulta = $con->prepare($stmt);
    $consulta->bindParam(':email', $email, PDO::PARAM_STR);  // Vinculamos el parámetro :email
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);  // Ejecutamos la consulta y devolvemos el resultado
}

function compruebaPedido($email, $cantidad, $prodID)
{
    $con = conectar();

    $stmt = $con->prepare('SELECT MAX(fechaEntrega) AS ultimaFechaEntrega FROM pedidos WHERE email = :email');
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $ultimaFechaEntrega = $stmt->fetchColumn();

    $fechaLimite = date('Y-m-d', strtotime($ultimaFechaEntrega . ' -1 month'));

    $stmt = $con->prepare(
        "SELECT SUM(itempedido.unidades) AS total 
         FROM pedidos 
         INNER JOIN itempedido ON pedidos.pedidoID = itempedido.pedidoID 
         WHERE pedidos.email = :email
         AND pedidos.fechaEntrega BETWEEN :fechaLimite AND :ultimaFechaEntrega"
    );
    
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':fechaLimite', $fechaLimite);
    $stmt->bindParam(':ultimaFechaEntrega', $ultimaFechaEntrega);
    $stmt->execute();
    
    $totalComprado = $stmt->fetchColumn() ?? 0;

    if (($totalComprado + $cantidad) > 2) {
        $proxCompra = ultimaFecha($email);
        echo 'No puedes añadir más de 2 artículos por mes. Tu próxima compra podrá ser realizada el ' . $proxCompra . '.';
        header('Refresh: 3; url=logout.php');
        exit();
    } else {
        $con = conectar();
        $cestaID = cestaID($email); 
        if ($cestaID === null) {
            
            echo 'Error: No se pudo encontrar una cesta válida. Intenta nuevamente.';
            return;
        }
        $stmt = 'INSERT INTO itemcesta (cestaID, productoID, cantidad) 
                 VALUES (:cestaID, :productoID, :cantidad)';

        $consulta = $con->prepare($stmt);
        $consulta->bindParam(':cestaID', $cestaID, PDO::PARAM_INT);
        $consulta->bindParam(':productoID', $prodID, PDO::PARAM_INT);
        $consulta->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);

        if ($consulta->execute()) {
            echo "Producto añadido a la cesta.<br>";
            echo $email;
        } else {
            echo "Error al añadir el producto.<br>";

        }
    }
}

function ultimaFecha($email)
{
    $con = conectar();

    // Obtener la última fecha de entrega del usuario
    $stmt = 'SELECT MAX(pedidos.fechaEntrega) AS ultimaFechaEntrega 
             FROM pedidos 
             WHERE pedidos.email = :email';

    $consulta = $con->prepare($stmt);
    $consulta->bindParam(':email', $email, PDO::PARAM_STR);
    $consulta->execute();
    $ultimaFecha = $consulta->fetch(PDO::FETCH_ASSOC)['ultimaFechaEntrega'];

    if ($ultimaFecha) {
        // Calcular la fecha de la próxima compra sumando 1 mes a la fecha de la última compra
        $proxCompra = date('Y-m-d', strtotime($ultimaFecha . ' +1 month'));
    } else {
        // Si no hay historial de compras, podemos asumir que la fecha de la próxima compra es un mes desde la fecha actual
        $proxCompra = date('Y-m-d', strtotime('+1 month'));
    }
    return $proxCompra;
}
 

?>
