<?php
require 'conexionPDO.php';

// Initialize error/success messages
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirmPassword = $_POST["confirm_password"] ?? '';
    
    // Extensive validation
    if (strlen($username) < 3) {
        $error = 'El nombre de usuario debe tener al menos 3 caracteres.';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'La contraseña debe contener al menos una letra mayúscula.';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = 'La contraseña debe contener al menos una letra minúscula.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'La contraseña debe contener al menos un número.';
    } elseif (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
        $error = 'La contraseña debe contener al menos un carácter especial (!@#$%^&*()-_=+{};:,<.>).';
    } else {
        try {
            // Check if username already exists
            $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $check->execute([$username]);
            if ($check->fetchColumn() > 0) {
                $error = 'Este nombre de usuario ya está en uso.';
            } else {
                // Hash password and insert user
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                
                if ($stmt->execute([$username, $hashedPassword])) {
                    $success = "¡Usuario registrado correctamente!";
                } else {
                    $error = "Error al registrar usuario.";
                }
            }
        } catch (PDOException $e) {
            $error = "Error en el servidor: " . $e->getMessage();
        }
    }

    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: transform 0.3s ease;
        }
        .register-card:hover {
            transform: translateY(-5px);
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .password-strength {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="register-card p-4 p-md-5">
                    <h2 class="text-center mb-4">Registro de Usuario</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success); ?>
                            <hr>
                            <p class="mb-0">
                                <a href="login.php" class="alert-link">Iniciar sesión</a>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if (!$success): ?>
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="username" 
                                       name="username" 
                                       placeholder="Elige un nombre de usuario"
                                       required 
                                       minlength="3"
                                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                                <div class="invalid-feedback">
                                    Por favor ingresa un nombre de usuario válido (mínimo 3 caracteres).
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Crea una contraseña segura"
                                       required 
                                       minlength="8"
                                       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()\-_=+{};:,<.>]).{8,}">
                                <div class="invalid-feedback">
                                    La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y caracteres especiales.
                                </div>
                                <div class="password-strength text-muted">
                                    La contraseña debe contener al menos:
                                    <ul class="mb-0">
                                        <li>8 caracteres</li>
                                        <li>Una letra mayúscula</li>
                                        <li>Una letra minúscula</li>
                                        <li>Un número</li>
                                        <li>Un carácter especial (!@#$%^&*()-_=+{};:,<.>)</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Repite tu contraseña"
                                       required>
                                <div class="invalid-feedback">
                                    Las contraseñas no coinciden.
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Registrarse
                                </button>
                                <a href="login.php" class="btn btn-outline-secondary">
                                    ¿Ya tienes cuenta? Inicia sesión
                                </a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom validation script -->
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            const password = document.getElementById('password')
            const confirmPassword = document.getElementById('confirm_password')

            // Password confirmation validation
            const validatePassword = () => {
                if (confirmPassword.value === password.value) {
                    confirmPassword.setCustomValidity('')
                } else {
                    confirmPassword.setCustomValidity('Las contraseñas no coinciden')
                }
            }

            password.addEventListener('change', validatePassword)
            confirmPassword.addEventListener('keyup', validatePassword)

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>