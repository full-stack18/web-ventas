<?php

include_once 'database.php';

include_once 'Cliente.php';


$database = new Database();

$db = $database->getConnection();

$cliente = new Cliente($db);


$cliente->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no encontrado.');


if($cliente->leerUno()){

    if($_POST){

        $cliente->nombre = $_POST['nombre'];

        $cliente->email = $_POST['email'];

        $cliente->telefono = $_POST['telefono'];

        $cliente->direccion = $_POST['direccion'];


        if($cliente->actualizar()){

            echo "<div class='alert alert-success'>Cliente actualizado exitosamente.</div>";

        } else{

            echo "<div class='alert alert-danger'>No se pudo actualizar el cliente.</div>";

        }

    }

} else {

    die('Cliente no encontrado.');

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Editar Cliente</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card mt-4">

                    <div class="card-header">

                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Cliente</h4>

                    </div>

                    <div class="card-body">

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$cliente->id}"); ?>" method="post">

                            <div class="mb-3">

                                <label for="nombre" class="form-label">Nombre</label>

                                <input type="text" class="form-control" id="nombre" name="nombre" 

                                       value="<?php echo $cliente->nombre; ?>" required>

                            </div>

                            

                            <div class="mb-3">

                                <label for="email" class="form-label">Email</label>

                                <input type="email" class="form-control" id="email" name="email" 

                                       value="<?php echo $cliente->email; ?>" required>

                            </div>

                            

                            <div class="mb-3">

                                <label for="telefono" class="form-label">Teléfono</label>

                                <input type="text" class="form-control" id="telefono" name="telefono" 

                                       value="<?php echo $cliente->telefono; ?>">

                            </div>

                            

                            <div class="mb-3">

                                <label for="direccion" class="form-label">Dirección</label>

                                <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo $cliente->direccion; ?></textarea>

                            </div>

                            

                            <button type="submit" class="btn btn-primary">Actualizar Cliente</button>

                            <a href="/Web Ventas/src/dist/dashboard.php" class="btn btn-secondary">Volver</a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>