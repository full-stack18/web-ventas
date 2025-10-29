<?php
//zona horaria predeterminada de PHP, en este caso la de Perú (UTC-5).

date_default_timezone_set('America/Lima');




require_once(__DIR__ . '/../vendor/autoload.php');

include_once 'database.php';

include_once 'Cliente.php';


// Crear conexión

$database = new Database();

$db = $database->getConnection();

$cliente = new Cliente($db);


// Obtener todos los clientes

$stmt = $cliente->leer();

$total_clientes = $cliente->contar();


// Crear PDF

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);


// Información del documento

$pdf->SetCreator('Sistema Clientes');

$pdf->SetAuthor('Administrador');

$pdf->SetTitle('Reporte de Clientes');

$pdf->SetSubject('Reporte PDF');


// Configurar márgenes

$pdf->SetMargins(15, 25, 15);

$pdf->SetHeaderMargin(10);

$pdf->SetFooterMargin(10);


// Agregar página

$pdf->AddPage();


// Header personalizado

$pdf->SetFillColor(58, 83, 155);

$pdf->SetTextColor(255);

$pdf->SetFont('helvetica', 'B', 20);

$pdf->Cell(0, 15, 'REPORTE COMPLETO DE CLIENTES', 0, 1, 'C', 1);

$pdf->Ln(5);


// Información del reporte

$pdf->SetTextColor(0);

$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1);

$pdf->Cell(0, 10, 'Total de clientes: ' . $total_clientes, 0, 1);

$pdf->Ln(10);


// Crear tabla

$html = '

<style>

    table {

        width: 100%;

        border-collapse: collapse;

        font-family: helvetica;

        font-size: 10px;

    }

    th {

        background-color: #2c3e50;

        color: white;

        font-weight: bold;

        padding: 8px;

        text-align: center;

        border: 1px solid #ddd;

    }

    td {

        padding: 6px;

        border: 1px solid #ddd;

        text-align: left;

    }

    tr:nth-child(even) {

        background-color: #f8f9fa;

    }

    .numero {

        text-align: center;

        font-weight: bold;

    }

</style>


<table>

    <thead>

        <tr>

            <th style="width: 8%;">ID</th>

            <th style="width: 22%;">Nombre</th>

            <th style="width: 25%;">Email</th>

            <th style="width: 15%;">Teléfono</th>

            <th style="width: 30%;">Dirección</th>

        </tr>

    </thead>

    <tbody>';


$contador = 0;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $contador++;

    $html .= '

        <tr>

            <td class="numero">' . $row['id'] . '</td>

            <td>' . htmlspecialchars($row['nombre']) . '</td>

            <td>' . htmlspecialchars($row['email']) . '</td>

            <td>' . htmlspecialchars($row['telefono']) . '</td>

            <td>' . htmlspecialchars($row['direccion']) . '</td>

        </tr>';

}


$html .= '

    </tbody>

</table>';


// Agregar resumen

$html .= '

<div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">

    <h4 style="color: #2c3e50; margin-bottom: 10px; font-size: 14px;">RESUMEN DEL REPORTE</h4>

    <p style="margin: 5px 0; font-size: 12px;"><strong>Total de clientes registrados:</strong> ' . $contador . '</p>

    <p style="margin: 5px 0; font-size: 12px;"><strong>Fecha de generación:</strong> ' . date('d/m/Y H:i:s') . '</p>

    <p style="margin: 5px 0; font-size: 10px; font-style: italic; color: #666;">

        Documento generado automáticamente por el sistema de gestión de clientes

    </p>

</div>';


// Escribir contenido

$pdf->writeHTML($html, true, false, true, false, '');


// Pie de página

$pdf->SetY(-15);

$pdf->SetFont('helvetica', 'I', 8);

$pdf->Cell(0, 10, 'Página ' . $pdf->getAliasNumPage() . ' de ' . $pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');


// Salida del PDF

$nombre_archivo = 'reporte_clientes_' . date('Y-m-d_H-i-s') . '.pdf';

$pdf->Output($nombre_archivo, 'D');

?>