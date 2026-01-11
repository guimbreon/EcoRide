<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>EcoRide</title>



    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="assets/css/theme.css" rel="stylesheet" />

  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-5 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
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
        <!--/.bg-holder-->

        <div class="container">
          <div class="row align-items-center">
            <h1 class="mb-4 fw-big">Sobre:</h1>
            <p>A EcoRide nasceu com um propósito claro:
            tornar as viagens mais acessíveis, convenientes e sustentáveis. 
            Somos uma plataforma de partilha de viagens (ridesharing) que conecta motoristas e passageiros que seguem na mesma direção, ajudando a reduzir custos e a pegada ecológica. 
            Acreditamos que cada viagem compartilhada significa menos trânsito, menos emissões de carbono e um impacto positivo no meio ambiente. Ao escolher a EcoRide, você não apenas economiza dinheiro, mas também contribui para um futuro mais sustentável.</p>
            <p>Junte-se a nós e transforme a forma como você se desloca!</p>
            <p></p>
            <p></p>
            <p></p>
            <?php
            include "scripts/abreconexao.php";
            $sql = "SELECT COUNT(*) AS total FROM Utilizadores";
            if ($result = $conn->query($sql)) {
              $row = $result->fetch_assoc();
              echo '<h3 class="mb-4 fw-small"> Número de utilizadores: ' . $row['total'] . '</h3>';
              $result->free();
            }
            $conn->close();
            ?>
            <!-- adicionar na segunda fase -->
            <p></p>
            <p></p>
            <p></p>
            <p></p> 
            <h4 class="mb-4 fw-medium">Criado por: Duarte Soares, Vitória Correia, Guilherme Soares</h4>
            <br>
            

                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
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
