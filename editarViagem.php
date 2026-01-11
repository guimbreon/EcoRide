<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: register.php");
    exit();
}

include "scripts/abreconexao.php";

$user = $_SESSION['user'];
$viagemId = $_GET['id'] ?? null;



// Verifica se o ID da viagem foi fornecido
if (!$viagemId) {
    header("Location: criarViagem.php?error=ID da viagem não fornecido.");
    exit();
}

// Busca os detalhes da viagem para preencher o formulário
$stmt = $conn->prepare("SELECT origem, destino, lugares, preco, data_hora FROM Viagens WHERE id = ? AND condutor_id = ?");
$stmt->bind_param("is", $viagemId, $user['email']);
$stmt->execute();
$result = $stmt->get_result();
$viagem = $result->fetch_assoc();
$stmt->close();

if (!$viagem) {
    header("Location: criarViagem.php?error=Viagem não encontrada.");
    exit();
}

// Formatar a data e hora para o campo datetime-local
$data_hora_formatada = str_replace(' ', 'T', $viagem['data_hora']);
?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Viagem - EcoRide</title>
    <link href="assets/css/theme.css" rel="stylesheet" />
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <h1>ECORIDE</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base align-items-lg-center align-items-start">
                    <?php include 'scripts/checkLogin.php'; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main" id="top">
        <section style="padding-top: 7rem;">
            <div class="container">
                <h1 class="text-center">Editar Viagem</h1>
                <form action="scripts/editarViagem.php" method="POST" class="text-start mx-auto" style="max-width: 400px;">
                    <input type="hidden" name="viagemId" value="<?php echo htmlspecialchars($viagemId, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="mb-3">
                        <label for="origem" class="form-label">Origem</label>
                        <select class="form-select" id="origem" name="origem" required>
                            <option value="" disabled>Selecione a origem</option>
                            <?php
                            $stmt = $conn->prepare("SELECT id, nome, rua, nmr, localidade FROM Local");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($row['id'] == $viagem['origem']) ? "selected" : "";
                                $label = htmlspecialchars(
                                    utf8_encode(
                                        $row['nome'] .
                                        (empty($row['rua']) ? '' : ', ' . $row['rua']) .
                                        (empty($row['nmr']) ? '' : ', ' . $row['nmr']) .
                                        (empty($row['localidade']) ? '' : ', ' . $row['localidade'])
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                echo "<option value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' $selected>$label</option>";
                            }
                            $stmt->close();
                            ?>
                            <option value="outro">Outro</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="origemOutro" name="origem_outro" placeholder="ex:. Nome, Rua, Numero, Localidade" style="display:none;" value="<?php echo isset($_POST['origem_outro']) ? htmlspecialchars($_POST['origem_outro']) : ''; ?>" />

                        <script>
                        // Função para mostrar/esconder campo "Outro" da origem
                        function mostrarOutroOrigem(select) {
                            var outroInput = document.getElementById('origemOutro');
                            if (select.value === 'outro') {
                                outroInput.style.display = 'block';
                                outroInput.required = true;
                            } else {
                                outroInput.style.display = 'none';
                                outroInput.required = false;
                                outroInput.value = '';
                            }
                        }
                        // Executa ao carregar a página para manter o campo se já estava selecionado
                        document.addEventListener('DOMContentLoaded', function() {
                            var select = document.getElementById('origem');
                            mostrarOutroOrigem(select);
                            select.addEventListener('change', function() {
                                mostrarOutroOrigem(this);
                            });
                        });
                        </script>
                    </div>
                    <div class="mb-3">
                        <label for="destino" class="form-label">Destino</label>
                        <select class="form-select" id="destino" name="destino" required>
                            <option value="" disabled>Selecione o destino</option>
                            <?php
                            $stmt = $conn->prepare("SELECT id, nome, rua, nmr, localidade FROM Local");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($row['id'] == $viagem['destino']) ? "selected" : "";
                                $label = htmlspecialchars(
                                    utf8_encode(
                                        $row['nome'] .
                                        (empty($row['rua']) ? '' : ', ' . $row['rua']) .
                                        (empty($row['nmr']) ? '' : ', ' . $row['nmr']) .
                                        (empty($row['localidade']) ? '' : ', ' . $row['localidade'])
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                echo "<option value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' $selected>$label</option>";
                            }
                            $stmt->close();
                            ?>
                            <option value="outro">Outro</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="destinoOutro" name="destino_outro" placeholder="ex:. Nome, Rua, Numero, Localidade" style="display:none;" value="<?php echo isset($_POST['destino_outro']) ? htmlspecialchars($_POST['destino_outro']) : ''; ?>"/>
                        <script>
                        // Função para mostrar/esconder campo "Outro" do destino
                        function mostrarOutroDestino(select) {
                            var outroInput = document.getElementById('destinoOutro');
                            if (select.value === 'outro') {
                                outroInput.style.display = 'block';
                                outroInput.required = true;
                            } else {
                                outroInput.style.display = 'none';
                                outroInput.required = false;
                                outroInput.value = '';
                            }
                        }
                        // Executa ao carregar a página para manter o campo se já estava selecionado
                        document.addEventListener('DOMContentLoaded', function() {
                            var selectDestino = document.getElementById('destino');
                            mostrarOutroDestino(selectDestino);
                            selectDestino.addEventListener('change', function() {
                                mostrarOutroDestino(this);
                            });
                        });
                        </script>
                        </script>
                    </div>
                    <div class="mb-3">
                        <label for="lugares" class="form-label">Lugares Disponíveis</label>
                        <input type="number" class="form-control" id="lugares" name="lugares" value="<?php echo htmlspecialchars($viagem['lugares'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço</label>
                        <input type="number" class="form-control" id="preco" name="preco" value="<?php echo htmlspecialchars($viagem['preco'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_hora" class="form-label">Data e Hora</label>
                        <input type="datetime-local" class="form-control" id="data_hora" name="data_hora" value="<?php echo htmlspecialchars($data_hora_formatada, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <?php
                    // Mensagem de erro se origem = destino
                    if (isset($_GET['error']) && $_GET['error'] === 'origem_destino_iguais') {
                        echo '<div class="alert alert-danger" role="alert">A origem e o destino não podem ser iguais.</div>';
                    }
                    ?>
                    <script>
                    // Validação no frontend para impedir origem = destino
                    document.addEventListener('DOMContentLoaded', function() {
                        var form = document.querySelector('form');
                        var origem = document.getElementById('origem');
                        var destino = document.getElementById('destino');

                        form.addEventListener('submit', function(e) {
                            if (origem.value === destino.value && origem.value !== "" && destino.value !== "") {
                                e.preventDefault();
                                alert('A origem e o destino não podem ser iguais.');
                            }
                        });
                    });
                    </script>
                    <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                </form>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container text-center text-md-left">
            <div class="row text-center text-md-left">
                <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">EcoRide</h5>
                    <p>EcoRide é a alternativa de transporte mais eficiente e sustentável. Partilhe a viagem e reduza a pegada de carbono!</p>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Links</h5>
                    <p><a href="index.php" class="text-white text-decoration-none">Home</a></p>
                    <p><a href="about.php" class="text-white text-decoration-none">Sobre nós</a></p>
                    <p><a href="contact.php" class="text-white text-decoration-none">Contactos</a></p>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Contactos</h5>
                    <p><i class="fas fa-home mr-3"></i> Lisboa, Portugal</p>
                    <p><i class="fas fa-envelope mr-3"></i> info@ecoride.com</p>
                    <p><i class="fas fa-phone mr-3"></i> +351 234 567 890</p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Siga-nos</h5>
                    <a href="#" class="text-white text-decoration-none"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white text-decoration-none"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white text-decoration-none"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-7 col-lg-8">
                    <p class="text-center text-md-left">© 2023 EcoRide. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>