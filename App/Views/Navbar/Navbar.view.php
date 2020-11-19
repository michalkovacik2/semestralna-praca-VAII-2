<?php session_start(); ?>
<div class="container">
    <nav class="navbar navbar-expand-md myNavbar">
        <a href="semestralka?c=MainPage" class="navbar-brand">
            <i class="fas fa-book-open"></i>
        </a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a href="semestralka?c=About" class="nav-item nav-link active">O knižnici</a>
                <a href="semestralka?c=Price" class="nav-item nav-link">Cenník</a>
                <a href="#" class="nav-item nav-link">Kontakt</a>
                <a href="semestralka?c=Reserve" class="nav-item nav-link">Rezervuj si knihu</a>

                <?php if (isset($_SESSION['user'])) { ?>
                    <a href="semestralka?c=Profil" class="nav-item nav-link"> <i class="fas fa-user"></i> Profil </a>
                <?php } ?>
            </div>
            <div class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user'])) { ?>
                    <a href="semestralka?c=MainPage&a=logout" class="nav-item nav-link">Odhlásiť sa</a>
                <?php } else { ?>
                    <a href="semestralka?c=Login" class="nav-item nav-link">Prihlásenie / Registrácia</a>
                <?php } ?>
            </div>
        </div>
    </nav>
</div>
