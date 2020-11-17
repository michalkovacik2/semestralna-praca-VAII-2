<title>Rezervácia</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
<link rel="stylesheet" href="semestralka/css/reserve_page.css">
</head>
<body>

<?php include "App/Views/Navbar/Navbar.view.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-sm-12 mt-4">

            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center categoryTitle">
                    <strong>Žánre</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioVsetky" name="category" checked>
                        <label class="custom-control-label" for="radioVsetky">Všetky</label>
                    </div>
                    <span class="badge orangeBadge">10 000</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioRomany" name="category">
                        <label class="custom-control-label" for="radioRomany">Romány</label>
                    </div>
                    <span class="badge orangeBadge">2 000</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioMladez" name="category">
                        <label class="custom-control-label" for="radioMladez">Pre deti a mládež</label>
                    </div>
                    <span class="badge orangeBadge">1 000</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioDetektivky" name="category">
                        <label class="custom-control-label" for="radioDetektivky">Detektívky</label>
                    </div>
                    <span class="badge orangeBadge">4 000</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioHistoricke" name="category">
                        <label class="custom-control-label" for="radioHistoricke">Historické</label>
                    </div>
                    <span class="badge orangeBadge">777</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioFantasy" name="category">
                        <label class="custom-control-label" for="radioFantasy">Sci-fi, fantasy</label>
                    </div>
                    <span class="badge orangeBadge">42</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioProza" name="category">
                        <label class="custom-control-label" for="radioProza">Próza</label>
                    </div>
                    <span class="badge orangeBadge">25</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="radioOdborna" name="category">
                        <label class="custom-control-label" for="radioOdborna">Odborná literatúra</label>
                    </div>
                    <span class="badge orangeBadge">11</span>
                </li>
            </ul>

        </div>

        <!-- prava strana -->
        <div class="col-md-8 col-sm-12 mt-4">
            <div class="input-group">
                <input type="text" class="form-control outlineButton" placeholder="Vyhľadanie" aria-label="Vyhľadanie" aria-describedby="searchButton">
                <button type="button" class="btn orangeFont outlineButton dropdown-toggle-split" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item dropDownItem" href="#">Názov knihy</a>
                    <a class="dropdown-item dropDownItem" href="#">Autor</a>
                    <a class="dropdown-item dropDownItem" href="#">Rok vydania</a>
                </div>
                <div class="input-group-append">
                    <button class="btn orangeFont outlineButton" type="button" id="searchButton"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <!-- Koniec search baru -->

            <!-- 1. polozka -->
            <div class="jumbotron-fluid bookItem">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                            <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                        </div>
                        <div class="col-xs-10 col-sm-10 col-md-10 paddingZero">
                            <div class="card">
                                <div class="card-body myCard">
                                    <h4>Hlava XXII</h4>
                                    <h6 class="card-subtitle mb-2 text-muted autor">
                                        Joseph Heller, <time datetime="1994">1994</time>
                                    </h6>
                                    <p class="shortInfo">
                                        Román Hlava XXII je jedným z najdôležitejších protivojnových diel, ktoré vyšli po druhej svetovej vojne.
                                        Autor opisuje márny boj hlavného hrdinu Yossariana proti absurdnej vojenskej mašinérii,
                                        pričom hojne využíva čierny humor a neustále balansuje medzi komikou a hororom.
                                    </p>
                                    <span>
                                            <i class="fas fa-check-circle dostupneIcon"></i> Dostupné 5ks
                                            <a href="#" class="btn buttonAvailable float-right">Rezervovať</a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. polozka -->
            <div class="jumbotron-fluid bookItem">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                            <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                        </div>
                        <div class="col-xs-10 col-sm-10 col-md-10 paddingZero">
                            <div class="card">
                                <div class="card-body myCard">
                                    <h4>
                                        451 stupňov Fahrenheita
                                    </h4>
                                    <h6 class="card-subtitle mb-2 text-muted autor">
                                        Ray Bradbury, <time datetime="2015">2015</time>
                                    </h6>
                                    <p class="shortInfo">
                                        Román Hlava XXII je jedným z najdôležitejších protivojnových diel, ktoré vyšli po druhej svetovej vojne.
                                        Autor opisuje márny boj hlavného hrdinu Yossariana proti absurdnej vojenskej mašinérii,
                                        pričom hojne využíva čierny humor a neustále balansuje medzi komikou a hororom.
                                    </p>
                                    <span>
                                            <i class="fas fa-check-circle dostupneIcon"></i> Dostupné 1ks
                                            <a href="#" class="btn buttonAvailable float-right">Rezervovať</a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. polozka -->
            <div class="jumbotron-fluid bookItem">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                            <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                        </div>
                        <div class="col-xs-10 col-sm-10 col-md-10 paddingZero">
                            <div class="card">
                                <div class="card-body myCard">
                                    <h4>Na západe nič nové</h4>
                                    <h6 class="card-subtitle mb-2 text-muted autor">
                                        Erich Maria Remarque, <time datetime="1929">1929</time>
                                    </h6>
                                    <p class="shortInfo">
                                        Román Hlava XXII je jedným z najdôležitejších protivojnových diel, ktoré vyšli po druhej svetovej vojne.
                                        Autor opisuje márny boj hlavného hrdinu Yossariana proti absurdnej vojenskej mašinérii,
                                        pričom hojne využíva čierny humor a neustále balansuje medzi komikou a hororom.
                                    </p>
                                    <span>
                                            <i class="fas fa-times-circle nedostupneIcon"></i> Nedostupné
                                            <a href="#" class="btn buttonUnavailable float-right disabled" >Rezervovať</a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. polozka -->
            <div class="jumbotron-fluid bookItem">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                            <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                        </div>
                        <div class="col-xs-10 col-sm-10 col-md-10 paddingZero">
                            <div class="card">
                                <div class="card-body myCard">
                                    <h4>Malý princ</h4>
                                    <h6 class="card-subtitle mb-2 text-muted autor">
                                        Antoine De Saint-Exupéry, <time datetime="1943">1943</time>
                                    </h6>
                                    <p class="shortInfo">
                                        Román Hlava XXII je jedným z najdôležitejších protivojnových diel, ktoré vyšli po druhej svetovej vojne.
                                        Autor opisuje márny boj hlavného hrdinu Yossariana proti absurdnej vojenskej mašinérii,
                                        pričom hojne využíva čierny humor a neustále balansuje medzi komikou a hororom.
                                    </p>
                                    <span>
                                            <i class="fas fa-check-circle dostupneIcon"></i> Dostupné 10ks
                                            <a href="#" class="btn buttonAvailable float-right">Rezervovať</a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. polozka -->
            <div class="jumbotron-fluid bookItem">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                            <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                        </div>
                        <div class="col-xs-10 col-sm-10 col-md-10 paddingZero">
                            <div class="card">
                                <div class="card-body myCard">
                                    <h4>Harry Potter a Kameň mudrcov</h4>
                                    <h6 class="card-subtitle mb-2 text-muted autor">
                                        J.K. Rowling, <time datetime="2001">2001</time>
                                    </h6>
                                    <p class="shortInfo">
                                        Román Hlava XXII je jedným z najdôležitejších protivojnových diel, ktoré vyšli po druhej svetovej vojne.
                                        Autor opisuje márny boj hlavného hrdinu Yossariana proti absurdnej vojenskej mašinérii,
                                        pričom hojne využíva čierny humor a neustále balansuje medzi komikou a hororom.
                                    </p>
                                    <span>
                                            <i class="fas fa-check-circle dostupneIcon"></i> Dostupné 5ks
                                            <a href="#" class="btn buttonAvailable float-right">Rezervovať</a>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav aria-label="Hladanie medzi knihami">
                <ul class="pagination justify-content-center mt-3">
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
        </div>
    </div>
</div>
