<?php
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    echo '
    <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="home.php">Home</a></li>
    ';
    if (isset($user['tipo']) && $user['tipo'] === 'Condutores') {
        echo '
                <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="profile.php">' . htmlspecialchars($user['nome']) . '</a></li>
                <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="criarViagem.php">Viagens</a></li>
                ';
    } elseif (isset($user['tipo']) && $user['tipo'] === 'Passageiros') {
        echo '
                <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="profile.php">' . htmlspecialchars($user['nome']) . '</a></li>
                <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="reservas.php">Pesquisar Viagens</a></li>
                ';
    } else {
        echo '
                <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="admin.php">Admin</a></li>
                <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="estatisticas.php">Estatisticas</a></li>
                ';
    }
    echo '
    <li class="nav-item px-3 px-xl-4">
        <a class="btn btn-outline-dark order-1 order-lg-0 fw-medium" href="scripts/logout.php" onclick="return confirm(\'Tem a certeza que quer faz LogOut?\')">Logout</a>
    </li>
    ';
} else {
    echo '
    <li class="nav-item px-3 px-xl-4"><a class="nav-link fw-medium" aria-current="page" href="login.php">Login</a></li>
    <li class="nav-item px-3 px-xl-4"><a class="btn btn-outline-dark order-1 order-lg-0 fw-medium" href="register.php">Sign Up</a></li>
    ';
}
?>