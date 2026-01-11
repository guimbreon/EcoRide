<?php
function is_strong_password($password) {
    return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

$errors = [];
$nome = $nif = $tlmv = $email = $pass = $type = '';
$file_path = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "scripts/abreconexao.php"; // Include your database connection file

    $nome = htmlspecialchars($_POST["nome"]);
    $nif = htmlspecialchars($_POST["nif"]);
    $tlmv = htmlspecialchars($_POST["tlmv"]);
    $email = htmlspecialchars($_POST["email"]);
    $pass = htmlspecialchars($_POST["pass"]);
    $type = htmlspecialchars($_POST["type"]);

    // Validate inputs
    if (!is_string($nome) || empty($nome) || mysqli_real_escape_string($conn, $nome) != $nome) {
        $errors[] = "Nome deve ser uma string válida.";
    }
    if (!preg_match('/^\d{9}$/', $nif) || mysqli_real_escape_string($conn, $nif) != $nif) {
        $errors[] = "NIF deve ter exatamente 9 caracteres numéricos.";
    }
    if (!preg_match('/^\d{9}$/', $tlmv) || mysqli_real_escape_string($conn, $tlmv) != $tlmv) {
        $errors[] = "Telemóvel deve ter exatamente 9 caracteres numéricos.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mysqli_real_escape_string($conn, $email) != $email) {
        $errors[] = "Email deve estar no formato algo@algo.algo.";
    }
    if (!is_strong_password($pass) || mysqli_real_escape_string($conn, $pass) != $pass) {
        $errors[] = "A senha deve ter pelo menos 8 caracteres e incluir letras maiúsculas e minúsculas, números e caracteres especiais.";
    }
    if (empty($type) || mysqli_real_escape_string($conn, $type) != $type) {
        $errors[] = "Selecione um dos tipos de usuário disponíveis!";
    }

    // Handle file upload (optional)
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists and rename if necessary
        $file_base_name = pathinfo($target_file, PATHINFO_FILENAME);
        $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
        $counter = 1;

        while (file_exists($target_file)) {
            $target_file = $target_dir . $file_base_name . "($counter)." . $file_extension;
            $counter++;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 2000000) {
            $errors[] = "Desculpe, o arquivo é muito grande. O tamanho máximo permitido é de 2MB.";
        }

        // Validate file type
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
        }

        // Move uploaded file
        if (empty($errors) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        } else {
            $errors[] = "Erro ao fazer upload do arquivo.";
        }
    }

    // Check if the email already exists in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT email FROM Utilizadores WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "O email já está registrado. Por favor, use outro email.";
        }

        $stmt->close();
    }

    // If no errors, redirect to scripts/insere.php
    if (empty($errors)) {
        $data = $_POST;
        $data['ft_perfil'] = $file_path;
        header("Location: scripts/insere.php?" . http_build_query($data));
        exit();
    }

    $conn->close(); // Close the database connection
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
                    <form action="register.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" class="form-control form-control-sm" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                                <div class="invalid-feedback">
                                    Por favor, insira o seu nome.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                <div class="invalid-feedback">
                                    Por favor, insira o seu email.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="tlmv" class="form-label">Telemóvel:</label>
                                <input type="text" class="form-control form-control-sm" id="tlmv" name="tlmv" value="<?php echo htmlspecialchars($tlmv); ?>" required>
                                <div class="invalid-feedback">
                                    Por favor, insira o seu número de telemóvel.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="nif" class="form-label">NIF:</label>
                                <input type="text" class="form-control form-control-sm" id="nif" name="nif" value="<?php echo htmlspecialchars($nif); ?>" required>
                                <div class="invalid-feedback">
                                    Por favor, insira o seu NIF.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="pass" class="form-label">Senha:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" id="pass" name="pass" value="<?php echo htmlspecialchars($pass); ?>" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-color: orange;">
                                        <img src="assets/img/eye.png" alt="Mostrar senha" id="togglePasswordIcon" style="width: 20px; height: 20px;">
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    Por favor, insira a sua senha.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="type" class="form-label">Tipo:</label>
                                <select class="form-control form-control-sm" id="type" name="type" required>
                                    <option value="">Selecione o Tipo</option>
                                    <option value="Condutores" <?php if ($type == 'Condutores') echo 'selected'; ?>>Condutor</option>
                                    <option value="Passageiros" <?php if ($type == 'Passageiros') echo 'selected'; ?>>Passageiro</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor, selecione um tipo.
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="mb-3" style="width: 100%; max-width: 550px;">
                                <label for="fileToUpload" class="form-label">Selecione uma imagem:</label>
                                <input type="file" class="form-control form-control-sm" id="fileToUpload" name="fileToUpload">
                                <div class="invalid-feedback">
                                    Por favor, selecione um arquivo válido.
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
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
</body>

</html>