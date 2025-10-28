<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <?php
include("conexion.php");

$sql = "SELECT * FROM ventas";

$con_sql = mysqli_query($conexion, $sql);


while ($rows = mysqli_fetch_array($con_sql)) {

    echo '

        <tr>

            <td>' . $rows['id'] . '</td>

            <td>' . $rows['vendedor'] . '</td>

            <td>' . $rows['direccion'] . '</td>

            <td>' . $rows['fechaventa'] . '</td>

            <td>

                <a class="btn btn-danger btn-xs" 

                   href="conexion_mostrar.php?eli_id=' . $rows['id'] . '">

                   Eliminar

                </a>

            </td>

            <td>

                <a class="btn btn-info btn-xs" 

                   href="conexion_detalle.php?det_id=' . $rows['id'] . '">

                   Detalle

                </a>

            </td>

        </tr>

    ';

}

?>
   



  </body>
</html>