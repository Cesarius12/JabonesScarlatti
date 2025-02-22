<?php
require_once ('./fpdf186/fpdf.php');  // Asegúrate de tener la librería FPDF incluida

function generarPDF($nombreUsuario, $productos, $total)
{
    // Crear una instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Setear fuente
    $pdf->SetFont('Arial', 'B', 16);

    // Título del documento
    $pdf->Cell(200, 10, 'Compra realizada por: ' . $nombreUsuario, 0, 1, 'C');
    $pdf->Ln(10);  // Salto de línea

    // Información de la compra
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(200, 10, 'Fecha: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
    $pdf->Ln(5);  // Salto de línea

    // Detalles de los productos: añadir la columna de cantidad
    $pdf->Cell(50, 10, 'Producto', 1);
    $pdf->Cell(30, 10, 'Cantidad', 1);
    $pdf->Cell(30, 10, 'Precio', 1);
    $pdf->Ln(10);  // Salto de línea

    foreach ($productos as $producto) {
        $pdf->Cell(50, 10, $producto['nombre'], 1);
        $pdf->Cell(30, 10, $producto['cantidad'], 1);  // Mostrar cantidad
        $pdf->Cell(30, 10, '$' . number_format($producto['precio'], 2), 1);
        $pdf->Ln(10);  // Salto de línea
    }

    // Total de la compra
    $pdf->Ln(10);
    $pdf->Cell(50, 10, 'Total', 1);
    $pdf->Cell(30, 10, '', 0);  // Espacio vacío para la cantidad
    $pdf->Cell(30, 10, '$' . number_format($total, 2), 1);
    $pdf->Ln(20);

    // Guardar el archivo PDF
    $directory = 'pdfs/';

    // Verificar si la carpeta existe, si no, crearla
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);  // 0777 da permisos completos
    }

    $filename = $directory . 'compra_' . $nombreUsuario . '_' . time() . '.pdf';
    $pdf->Output('F', $filename);  // Guardar en la carpeta pdfs

    return $filename;  // Retornar el nombre del archivo PDF generado
}
?>
