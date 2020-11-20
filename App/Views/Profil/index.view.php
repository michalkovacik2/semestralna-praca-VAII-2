<?php /** @var $data[] */ ?>
<title>Profil</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
<link rel="stylesheet" href="semestralka/css/reserve_page.css">
<link rel="stylesheet" href="semestralka/css/profil_page.css">
</head>
<body>
<?php include "App/Views/Navbar/Navbar.view.php"; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12 col-xs-12 mt-4 infoPanel">
            <table class="table">
                <thead>
                <tr>
                    <th colspan="2">
                        <i class="fas fa-user-circle"></i> Profilové údaje
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Meno</th>
                    <td><?= $_SESSION['user']->getName(); ?></td>
                </tr>
                <tr>
                    <th>Priezvisko</th>
                    <td><?= $_SESSION['user']->getSurname(); ?></td>
                </tr>
                <tr>
                    <th>Emailová adresa</th>
                    <td><?= $_SESSION['user']->getEmail(); ?></td>
                </tr>
                <tr>
                    <th>Telefónne číslo</th>
                    <td><?= $_SESSION['user']->getPhoneFormated(); ?></td>
                </tr>
                <tr>
                    <th>Členom od</th>
                    <td><?= $_SESSION['user']->getMemberFromFormated(); ?></td>
                </tr>
                <tr>
                    <th>Členský poplatok platí do</th>
                    <td><?= (is_null($_SESSION['user']->getPaymentFrom()) ? "neuhradené" : $_SESSION['user']->getPaymentFrom());  ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12 mt-4">
            <div class="container d-flex flex-column" id="changePassword">
                <h2 class="text-left pt-1" id="loginLabel"><i class="fas fa-key"></i> Zmena hesla</h2>
                <form action="semestralka?c=Profil" method="post" id="resetPass">
                    <?php if (!is_null($data) && isset($data['success']) && $data['success'] == true )  { ?>
                        <div class="alert alert-success" role="alert">
                            Vaše heslo bolo úspešne zmenené.
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="idHeslo" class="font-weight-bold">Staré heslo</label>
                        <input type="password" class="form-control" id="idHeslo" placeholder="Staré heslo" name="oldPassword">
                    </div>
                    <?php if (!is_null($data) && isset($data['oldPassword'])) {
                        foreach ($data['oldPassword'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                    <div class="form-group">
                        <label for="idNoveHeslo" class="font-weight-bold">Nové heslo</label>
                        <input type="password" class="form-control" id="idNoveHeslo" placeholder="Nové heslo" name="newPassword">
                    </div>
                    <?php if (!is_null($data) && isset($data['newPassword'])) {
                        foreach ($data['newPassword'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                    <div class="form-group">
                        <label for="idNoveHeslo2" class="font-weight-bold">Zopakujte nové heslo</label>
                        <input type="password" class="form-control" id="idNoveHeslo2" placeholder="Nové heslo" name="newPassword2">
                    </div>
                    <?php if (!is_null($data) && isset($data['newPassword2'])) {
                        foreach ($data['newPassword2'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                </form>
                <button type="submit" form="resetPass" class="mt-auto btn btn-lg btn-block btn-dark">Zmeniť heslo</button>
            </div>
        </div>
    </div>

</div>

<!-- History -->
<div class="container mt-5 col-10 col-md-10">
    <div class="row justify-content-center">

        <div class="container">
            <h1 class="text-center">Moja história</h1>
        </div>

        <!-- historia 1 -->
        <div class="jumbotron-fluid bookItem w-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                        <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 paddingZero">
                        <div class="card middlePart">
                            <div class="card-body myCard">
                                <h4>Hlava XXII</h4>
                                <h6 class="card-subtitle mb-2 text-muted autor">
                                    Joseph Heller, <time datetime="1994">1994</time>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 paddingZero">
                        <div class="card">
                            <div class="card-body myCard">
                                <p>
                                    <strong>Rezervované:</strong> <br>
                                    <time datetime="2020-10-20">20.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vyzdvihnuté:</strong> <br>
                                    <time datetime="2020-10-22">22.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vrátené:</strong> <br>
                                    <time datetime="2020-11-15">15.11.2020</time>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historia 2 -->
        <div class="jumbotron-fluid bookItem w-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                        <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 paddingZero">
                        <div class="card middlePart">
                            <div class="card-body myCard">
                                <h4>Malý princ</h4>
                                <h6 class="card-subtitle mb-2 text-muted autor">
                                    Antoine De Saint-Exupéry, <time datetime="1943">1943</time>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 paddingZero">
                        <div class="card">
                            <div class="card-body myCard">
                                <p>
                                    <strong>Rezervované:</strong> <br>
                                    <time datetime="2020-10-20">20.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vyzdvihnuté:</strong> <br>
                                    <time datetime="2020-10-22">22.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vrátené:</strong> <br>
                                    <time datetime="2020-11-15">15.11.2020</time>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historia 3 -->
        <div class="jumbotron-fluid bookItem w-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                        <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 paddingZero">
                        <div class="card middlePart">
                            <div class="card-body myCard">
                                <h4>Harry Potter a Kameň mudrcov</h4>
                                <h6 class="card-subtitle mb-2 text-muted autor">
                                    J.K. Rowling, <time datetime="1994">2001</time>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 paddingZero">
                        <div class="card">
                            <div class="card-body myCard">
                                <p>
                                    <strong>Rezervované:</strong> <br>
                                    <time datetime="2020-10-20">20.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vyzdvihnuté:</strong> <br>
                                    <time datetime="2020-10-22">22.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vrátené:</strong> <br>
                                    <time datetime="2020-11-15">15.11.2020</time>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historia 4 -->
        <div class="jumbotron-fluid bookItem w-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                        <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 paddingZero">
                        <div class="card middlePart">
                            <div class="card-body myCard">
                                <h4>Na západe nič nové</h4>
                                <h6 class="card-subtitle mb-2 text-muted autor">
                                    Erich Maria Remarque, <time datetime="1929">1929</time>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 paddingZero">
                        <div class="card">
                            <div class="card-body myCard">
                                <p>
                                    <strong>Rezervované:</strong> <br>
                                    <time datetime="2020-10-20">20.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vyzdvihnuté:</strong> <br>
                                    <time datetime="2020-10-22">22.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vrátené:</strong> <br>
                                    <time datetime="2020-11-15">15.11.2020</time>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historia 5 -->
        <div class="jumbotron-fluid bookItem w-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                        <img src="semestralka/img/iconBook.png" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 paddingZero">
                        <div class="card middlePart">
                            <div class="card-body myCard">
                                <h4>451 stupňov Fahrenheita</h4>
                                <h6 class="card-subtitle mb-2 text-muted autor">
                                    Ray Bradbury, <time datetime="2015">2015</time>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 paddingZero">
                        <div class="card">
                            <div class="card-body myCard">
                                <p>
                                    <strong>Rezervované:</strong> <br>
                                    <time datetime="2020-10-20">20.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vyzdvihnuté:</strong> <br>
                                    <time datetime="2020-10-22">22.10.2020</time>
                                </p>
                                <p>
                                    <strong>Vrátené:</strong> <br>
                                    <time datetime="2020-11-15">15.11.2020</time>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav aria-label="Hladanie v historii">
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