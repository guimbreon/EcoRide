<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "scripts/abreconexao.php";

    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Por favor, insira um email válido.";
    }
    if (empty($password)) {
        $errors[] = "Por favor, insira a sua senha.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT email, pass, nome, ultimo_login FROM Utilizadores WHERE email = ?");
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $stmtCondutor = $conn->prepare("SELECT id FROM Condutores WHERE id = ?");
        if (!$stmtCondutor) {
          die("Erro ao preparar a consulta: " . $conn->error);
        }
        $stmtCondutor->bind_param("s", $email);
        $stmtCondutor->execute();
        $resultCondutor = $stmtCondutor->get_result();


        $stmtCondutor->close();

        $updateLogin = $conn->prepare("UPDATE Utilizadores SET ultimo_login = NOW() WHERE email = ?");
        if ($updateLogin) {
            $updateLogin->bind_param("s", $email);
            $updateLogin->execute();
            $updateLogin->close();
        }

        if ($user && password_verify($password, $user['pass'])) {
            $_SESSION['user'] = [
                'email' => $user['email'],
                'nome' => $user['nome'],
                'tipo' => ($email === 'Admin15@Admin.Admin') ? 'Admin' : (($resultCondutor->num_rows > 0) ? 'Condutores' : 'Passageiros'),
                'last_login' => $user['ultimo_login']
            ];
            header("Location: home.php");
            exit();
        } else {
            $errors[] = "Email ou senha incorretos.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-PT" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoRide</title>
    <link href="assets/css/theme.css" rel="stylesheet" />
</head>

<body>
    <main class="main" id="top">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 d-block shadow background-color: #f8f9fa;" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand" href="index.php"><h1>ECORIDE</h1></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> </span></button>
        <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">
        <?php include 'scripts/checkLogin.php'; ?>
        </ul>
            <li class="nav-item dropdown px-3 px-lg-0"> <a class="d-inline-block ps-0 py-2 pe-3 text-decoration-none dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">EN</a>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius:0.3rem;" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#!">PT</a></li>
        </ul>
            </li>
        </ul>
            </div>
        </div>
        </nav>
        <section style="padding-top: 7rem;">
            <div class="bg-holder" style="background-image:url(assets/img/hero/hero-bg.svg);">
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <form action="login.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                <div class="invalid-feedback">
                                    Por favor, insira o seu email.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="pass" class="form-label">Senha:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" id="pass" name="password" value="<?php echo htmlspecialchars($password ?? ''); ?>" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-color: orange;">
                                        <img src="assets/img/eye.png" alt="Mostrar senha" id="togglePasswordIcon" style="width: 20px; height: 20px;">
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    Por favor, insira a sua senha.
                                </div>
                            </div>
                        </div>
                        <script>
                            document.getElementById('togglePassword').addEventListener('click', function () {
                                const passwordField = document.getElementById('pass');
                                const icon = document.getElementById('togglePasswordIcon');
                                if (passwordField.type === 'password') {
                                    passwordField.type = 'text';
                                    icon.src = 'assets/img/hidden.png'; // Icon for "hide password"
                                    icon.alt = 'Ocultar senha';
                                } else {
                                    passwordField.type = 'password';
                                    icon.src = 'assets/img/eye.png'; // Icon for "show password"
                                    icon.alt = 'Mostrar senha';
                                }
                            });
                        </script>
                        <?php
                        if (!empty($errors)) {
                            echo "<div class='alert alert-danger'><ul>";
                            foreach ($errors as $error) {
                                echo "<li>$error</li>";
                            }
                            echo "</ul></div>";
                        }
                        ?>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
</body>

</html>