document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector(".navbar");

    // Função para atualizar o background com base no tamanho da janela
    function updateNavbarBackground() {
        if (window.innerWidth >= 992) {
            navbar.classList.add("no-background");
        } else {
            navbar.classList.remove("no-background");
        }
    }

    // Chama a função ao carregar a página
    updateNavbarBackground();

    // Adiciona um listener para o evento de resize
    window.addEventListener("resize", updateNavbarBackground);

    // Código existente para o botão navbar-toggler
    const navbarToggler = document.querySelector(".navbar-toggler");
    const navbarCollapse = document.querySelector("#navbarSupportedContent");

    navbarToggler.addEventListener("click", function () {
        navbarCollapse.classList.toggle("show");
    });

    const navLinks = navbarCollapse.querySelectorAll(".nav-link");
    navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            navbarCollapse.classList.remove("show");
        });
    });
});