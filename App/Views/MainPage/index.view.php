<title>Hlavná stránka</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
</head>

<body>
<div id="banner">
<?php include "App/Views/Navbar/Navbar.view.php"; ?>

    <div class="jumbotron jumbotron-fluid  bg-transparent">
        <div class="container">
            <h1 class="display-2 bannerText">Knižnica</h1>
        </div>
    </div>
</div>

<div class="container">
    <h1 class="display-3" id="novinky"> Novinky </h1>
</div>

<div class="container infoCards">
    <div class="row">
        <!-- Karta -->
        <div class="col-md-6 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <img src="semestralka/img/news1.png" class="card-img-top" alt="Obrazok otvorenej knihy" >
                <div class="card-body">
                    <h2 class="card-title">Nová kniha od Larsa Keplera !</h2>
                    <p class="card-text">
                        Od dnešného dňa si môžete prečítať výbornú knihu Zrkadlový muž, ktorá si získala veľmi veľa čitateľov
                        po celom svete. Neváhajte a knihu si rezervujte na našom <a href="#">webe</a>.
                    </p>
                </div>
                <footer>
                    <small class="text-muted float-right m-2">
                        <time datetime="2020-09-29 06:05">29.9.2020 06:05</time>
                    </small>
                </footer>
            </div>
        </div>
        <!-- Karta -->
        <div class="col-md-6 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <img src="semestralka/img/news2.png" class="card-img-top" alt="Stormbreaker obal knihy" >
                <div class="card-body">
                    <h2 class="card-title">Stormbreaker už aj u nás</h2>
                    <p class="card-text">
                        Nový detektívny príbeh od Vašeho obľúbeného autora. Aké dobrodružstvo čaká nášho hrdinu, to
                        sa dozviete v obľúbenej knihe <strong>Stormbreaker</strong>.
                        Kniha bola vo svete natoľko úspešná, že sa pripravuje filmová verzia. <br>
                        Neváhajte a rezervujte si túto knižku na našej stránke.
                    </p>
                </div>
                <footer>
                    <small class="text-muted float-right m-2">
                        <time datetime="2020-09-14 12:05">14.9.2020 12:05</time>
                    </small>
                </footer>
            </div>
        </div>
        <!-- Karta -->
        <div class="col-md-6 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <img src="semestralka/img/news3.png" class="card-img-top" alt="kluc a kvet na knihe" >
                <div class="card-body">
                    <h2 class="card-title">Súťažte s knižnicou</h2>
                    <p class="card-text">
                        Zapojte sa do súťaže a vyhrajte vecné ceny. Stačí napísať krátku básničku o knihe,
                        poprípade o autorovi. A zašlite nám ju na náš email.
                    </p>
                </div>
                <footer>
                    <small class="text-muted float-right m-2">
                        <time datetime="2020-07-16 20:00">16.7.2020 20:00</time>
                    </small>
                </footer>
            </div>
        </div>
        <!-- Karta -->
        <div class="col-md-6 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <img src="semestralka/img/news4.png" class="card-img-top" alt="zlata kniha" >
                <div class="card-body">
                    <h2 class="card-title">Najobľúbenejšia kniha </h2>
                    <p class="card-text">
                        Zahlasujte o najlepšiu knihu roka na našej stránke.
                    </p>
                </div>
                <footer>
                    <small class="text-muted float-right m-2">
                        <time datetime="2020-04-12 18:50">12.4.2020 18:50</time>
                    </small>
                </footer>
            </div>
        </div>
        <!-- Karta -->
        <div class="col-md-6 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <img src="semestralka/img/news1.png" class="card-img-top" alt="A aj tento" >
                <div class="card-body">
                    <h2 class="card-title">Dalsia novinka !</h2>
                    <p class="card-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus asperiores assumenda iusto
                        libero optio quis repellendus, unde? Cum deleniti esse eveniet fugiat quam sit tempore tenetur?
                        Culpa enim impedit ipsam.
                    </p>
                </div>
                <footer>
                    <small class="text-muted float-right m-2">
                        <time datetime="2019-01-11 07:20">11.1.2019 07:20</time>
                    </small>
                </footer>
            </div>
        </div>
        <!-- Karta -->
        <div class="col-md-6 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <img src="semestralka/img/news2.png" class="card-img-top" alt="Tento obrazok tu uz bol" >
                <div class="card-body">
                    <h2 class="card-title">Dalsia novinka !</h2>
                    <p class="card-text">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus asperiores assumenda iusto
                        libero optio quis repellendus, unde?
                    </p>
                </div>
                <footer>
                    <small class="text-muted float-right m-2">
                        <time datetime="2019-03-02 09:23">2.3.2019 09:23</time>
                    </small>
                </footer>
            </div>
        </div>
    </div>
</div>

<nav aria-label="Hladanie medzi novinkami">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">
                <i class="fas fa-arrow-left"></i>
            </a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">4</a></li>
        <li class="page-item"><a class="page-link" href="#">5</a></li>
        <li class="page-item"><a class="page-link" href="#">6</a></li>
        <li class="page-item">
            <a class="page-link" href="#">
                <i class="fas fa-arrow-right"></i>
            </a>
        </li>
    </ul>
</nav>
