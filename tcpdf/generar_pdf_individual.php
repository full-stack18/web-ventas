<?php

//zona horaria predeterminada de PHP, en este caso la de Perú (UTC-5).

date_default_timezone_set('America/Lima');



require_once(__DIR__ . '/../vendor/autoload.php');

include_once 'database.php';

include_once 'Cliente.php';


// Verificar si se proporcionó ID

if (!isset($_GET['id']) || empty($_GET['id'])) {

    die('ID de cliente no especificado');

}


$id_cliente = $_GET['id'];


// Crear conexión

$database = new Database();

$db = $database->getConnection();

$cliente = new Cliente($db);


// Obtener datos del cliente específico

$cliente->id = $id_cliente;


// Depuración: verificar si el cliente existe

if (!$cliente->leerUno()) {

    die('Cliente no encontrado con ID: ' . $id_cliente);

}


// Depuración: mostrar datos (quitar en producción)

error_log("Datos del cliente - ID: " . $cliente->id . ", Nombre: " . $cliente->nombre);


// Crear PDF

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);


// Información del documento

$pdf->SetCreator('Sistema Clientes');

$pdf->SetAuthor('Administrador');

$pdf->SetTitle('Ficha de Cliente - ' . $cliente->nombre);

$pdf->SetSubject('Ficha Individual');


// Configurar márgenes

$pdf->SetMargins(15, 25, 15);

$pdf->SetHeaderMargin(10);

$pdf->SetFooterMargin(10);


// Agregar página

$pdf->AddPage();


// Header

$pdf->SetFillColor(58, 83, 155);

$pdf->SetTextColor(255);

$pdf->SetFont('helvetica', 'B', 16);

$pdf->Cell(0, 15, 'FICHA INDIVIDUAL DE CLIENTE', 0, 1, 'C', 1);

$pdf->Ln(5);


// Información del cliente

$pdf->SetTextColor(0);

$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1);

$pdf->Ln(5);


// Contenido HTML

$html = '

<style>

    .info-table {

        width: 100%;

        border-collapse: collapse;

        margin: 10px 0;

        font-family: helvetica;

        font-size: 12px;

    }

    .info-table td {

        padding: 12px;

        border: 1px solid #ddd;

    }

    .label {

        background-color: #f8f9fa;

        font-weight: bold;

        width: 30%;

        color: #2c3e50;

    }

    .value {

        background-color: white;

    }

    .header-section {

        background-color: #2c3e50;

        color: white;

        padding: 10px;

        text-align: center;

        font-weight: bold;

        margin: 15px 0 10px 0;

    }

</style>


<div class="header-section">INFORMACIÓN DEL CLIENTE</div>


<table class="info-table">

    <tr>

        <td class="label">ID Cliente:</td>

        <td class="value"><strong>#' . $cliente->id . '</strong></td>

    </tr>

    <tr>

        <td class="label">Nombre Completo:</td>

        <td class="value">' . htmlspecialchars($cliente->nombre) . '</td>

    </tr>

    <tr>

        <td class="label">Email:</td>

        <td class="value">' . htmlspecialchars($cliente->email) . '</td>

    </tr>

    <tr>

        <td class="label">Teléfono:</td>

        <td class="value">' . htmlspecialchars($cliente->telefono) . '</td>

    </tr>

    <tr>

        <td class="label">Dirección:</td>

        <td class="value">' . htmlspecialchars($cliente->direccion) . '</td>

    </tr>

</table>


<div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">

    <p style="margin: 5px 0;"><strong>Fecha de emisión:</strong> ' . date('d/m/Y H:i:s') . '</p>

    <p style="margin: 5px 0;"><strong>Documento:</strong> Ficha Individual de Cliente</p>

    <p style="margin: 5px 0; font-style: italic; color: #666; font-size: 10px;">

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

$nombre_archivo = 'cliente_' . $cliente->id . '_' . date('Y-m-d') . '.pdf';

$pdf->Output($nombre_archivo, 'D');


// Para depuración (quitar en producción)

echo "<!-- Debug: Cliente ID: " . $cliente->id . " -->";

echo "<!-- Debug: Cliente Nombre: " . $cliente->nombre . " -->";

?>

