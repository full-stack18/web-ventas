<?php

include_once 'database.php';

include_once 'Cliente.php';


$database = new Database();

$db = $database->getConnection();

$cliente = new Cliente($db);


if($_POST){

    $cliente->nombre = $_POST['nombre'];

    $cliente->email = $_POST['email'];

    $cliente->telefono = $_POST['telefono'];

    $cliente->direccion = $_POST['direccion'];


    if($cliente->crear()){

        echo "<div class='alert alert-success'>Cliente creado exitosamente.</div>";

    } else{

        echo "<div class='alert alert-danger'>No se pudo crear el cliente.</div>";

    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Crear Cliente</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card mt-4">

                    <div class="card-header">

                        <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Crear Nuevo Cliente</h4>

                    </div>

                    <div class="card-body">

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                            <div class="mb-3">

                                <label for="nombre" class="form-label">Nombre</label>

                                <input type="text" class="form-control" id="nombre" name="nombre" required>

                            </div>

                            

                            <div class="mb-3">

                                <label for="email" class="form-label">Email</label>

                                <input type="email" class="form-control" id="email" name="email" required>

                            </div>

                            

                            <div class="mb-3">

                                <label for="telefono" class="form-label">Teléfono</label>

                                <input type="text" class="form-control" id="telefono" name="telefono">

                            </div>

                            

                            <div class="mb-3">

                                <label for="direccion" class="form-label">Dirección</label>

                                <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>

                            </div>

                            

                            <button type="submit" class="btn btn-primary">Crear Cliente</button>

                            <a href="index.php" class="btn btn-secondary">Volver</a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>