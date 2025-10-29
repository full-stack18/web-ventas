<?php

include_once 'database.php';

include_once 'Cliente.php';


$database = new Database();

$db = $database->getConnection();

$cliente = new Cliente($db);


$stmt = $cliente->leer();

$num = $stmt->rowCount();

$total_clientes = $cliente->contar();

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CRUD de Clientes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>

        .header-crud {

            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

            color: white;

            padding: 25px 0;

            margin-bottom: 30px;

        }

        .btn-pdf {

            background: #dc3545;

            color: white;

            border: none;

        }

        .btn-pdf:hover {

            background: #c82333;

            color: white;

        }

        .table-actions .btn {

            margin: 2px;

            padding: 5px 10px;

        }

    </style>

</head>

<body>

    <div class="header-crud">

        <div class="container">

            <h1 class="text-center"><i class="fas fa-users me-2"></i>Gestión de Clientes</h1>

            <p class="text-center mb-0">Sistema CRUD completo con generación de PDF</p>

        </div>

    </div>


    <div class="container">

        <!-- Botones de Acción -->

        <div class="row mb-4">

            <div class="col-md-6">

                <a href="crear.php" class="btn btn-primary">

                    <i class="fas fa-plus-circle me-1"></i>Nuevo Cliente

                </a>

                <a href="generar_pdf.php" class="btn btn-pdf" target="_blank">

                    <i class="fas fa-file-pdf me-1"></i>Generar PDF

                </a>

            </div>

            <div class="col-md-6 text-end">

                <span class="badge bg-secondary fs-6">

                    <i class="fas fa-chart-bar me-1"></i>

                    Total: <?php echo $total_clientes; ?> clientes

                </span>

            </div>

        </div>


        <!-- Tabla de Clientes -->

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">

                    <i class="fas fa-list me-2"></i>Lista de Clientes

                </h5>

            </div>

            <div class="card-body">

                <?php if($num > 0): ?>

                <div class="table-responsive">

                    <table class="table table-striped table-hover">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Nombre</th>

                                <th>Email</th>

                                <th>Teléfono</th>

                                <th>Dirección</th>

                                <th>Acciones</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>

                            <tr>

                                <td><?php echo $row['id']; ?></td>

                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>

                                <td><?php echo htmlspecialchars($row['email']); ?></td>

                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>

                                <td><?php echo htmlspecialchars($row['direccion']); ?></td>

                                <td class="table-actions">

                                    <a href="generar_pdf_individual.php?id=<?php echo $row['id']; ?>" 

                                       class="btn btn-warning btn-sm" 

                                       target="_blank"

                                       title="Generar PDF">

                                        <i class="fas fa-file-pdf"></i>

                                    </a>

                                    <a href="editar.php?id=<?php echo $row['id']; ?>" 

                                       class="btn btn-primary btn-sm"

                                       title="Editar">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    <a href="eliminar.php?id=<?php echo $row['id']; ?>" 

                                       class="btn btn-danger btn-sm"

                                       onclick="return confirm('¿Estás seguro de eliminar este cliente?')"

                                       title="Eliminar">

                                        <i class="fas fa-trash"></i>

                                    </a>

                                </td>

                            </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

                <?php else: ?>

                <div class="alert alert-warning text-center">

                    <i class="fas fa-exclamation-triangle me-2"></i>No hay clientes registrados.

                </div>

                <?php endif; ?>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php