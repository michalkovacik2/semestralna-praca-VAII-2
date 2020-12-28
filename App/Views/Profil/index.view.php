<?php /** @var $data[] */ ?>
<?php /** @var \App\Core\AAuthenticator $auth */ ?>
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
                    <th>Meno
                        <span data-toggle="modal" data-target="#myModalName" class="dialogButton"> <i class="fas fa-pen ml-2"></i> </span>
                    </th>
                    <td><?= $auth->getLoggedUser()->getName(); ?></td>
                </tr>
                <tr>
                    <th>Priezvisko
                        <span data-toggle="modal" data-target="#myModalSurname" class="dialogButton"> <i class="fas fa-pen ml-2"></i> </span>
                    </th>
                    <td><?= $auth->getLoggedUser()->getSurname(); ?></td>
                </tr>
                <tr>
                    <th>Emailová adresa</th>
                    <td><?= $auth->getLoggedUser()->getEmail(); ?></td>
                </tr>
                <tr>
                    <th>Telefónne číslo
                        <span data-toggle="modal" data-target="#myModalPhone" class="dialogButton"> <i class="fas fa-pen ml-2"></i> </span>
                    </th>
                    <td><?= $auth->getLoggedUser()->getPhoneFormated(); ?></td>
                </tr>
                <tr>
                    <th>Členom od</th>
                    <td><?= $auth->getLoggedUser()->getMemberFromFormated(); ?></td>
                </tr>
                <tr>
                    <th>Členský poplatok platí do</th>
                    <td><?= (is_null($auth->getLoggedUser()->getPaymentFrom()) ? "neuhradené" : $auth->getLoggedUser()->getPaymentFrom());  ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-12 mt-4">
            <div class="container d-flex flex-column" id="changePassword">
                <h2 class="text-left pt-1" id="loginLabel"><i class="fas fa-key"></i> Zmena hesla</h2>
                <form action="semestralka?c=Profil&page=<?= $_GET['page'] ?>" method="post" id="resetPass">
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

<div class="modal" id="myModalName">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="modal-title">Zmena mena</h2>
                <button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <form action="semestralka?c=Profil&page=<?= $_GET['page'] ?>" method="post">
                <div class="modal-body text-left">
                    <label for="idMeno" class="font-weight-bold">Zadajte nové meno: </label>
                    <input type="text" class="form-control" id="idMeno" name="name" placeholder="Meno" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['name']; ?>">
                    <?php if (isset($data['nameErrors'])) { ?>
                        <?php foreach ($data['nameErrors'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Zmeniť</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="myModalSurname">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="modal-title">Zmena priezviska</h2>
                <button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <form action="semestralka?c=Profil&page=<?= $_GET['page'] ?>" method="post">
                <div class="modal-body text-left">
                    <label for="idSurname" class="font-weight-bold">Zadajte nové priezvisko: </label>
                    <input type="text" class="form-control" id="idSurname" name="surname" placeholder="Priezvisko" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['surname']; ?>">
                    <?php if (isset($data['surnameErrors'])) { ?>
                        <?php foreach ($data['surnameErrors'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Zmeniť</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="myModalPhone">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="modal-title">Zmena čísla</h2>
                <button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <form action="semestralka?c=Profil&page=<?= $_GET['page'] ?>" method="post">
                <div class="modal-body text-left">
                    <label for="idPhone" class="font-weight-bold">Zadajte nové číslo: </label>
                    <input type="text" class="form-control" id="idPhone" name="phone" placeholder="Tel. číslo" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['phone']; ?>">
                    <?php if (isset($data['phoneErrors'])) { ?>
                        <?php foreach ($data['phoneErrors'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Zmeniť</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($data['nameErrors'])) { ?>
    <script>
        $(document).ready(function(){ $('#myModalName').modal('show'); });
    </script>
<?php } ?>

<?php if (isset($data['surnameErrors'])) { ?>
    <script>
        $(document).ready(function(){ $('#myModalSurname').modal('show'); });
    </script>
<?php } ?>

<?php if (isset($data['phoneErrors'])) { ?>
    <script>
        $(document).ready(function(){ $('#myModalPhone').modal('show'); });
    </script>
<?php } ?>

<!-- History -->
<div class="container mt-5 col-10 col-md-10">
    <div class="row justify-content-center">

        <div class="container">
            <h1 class="text-center">Moja história</h1>
        </div>

        <?php if (empty($data['history'])) { ?>
            <div class="container">
                <h4 class="text-center">Zatiaľ ste si nič nepožičali</h4>
            </div>
        <?php } else { ?>
        <?php foreach ($data['history'] as $history) { ?>
            <?php /** @var \App\Models\History $history */ ?>
            <div class="jumbotron-fluid bookItem w-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 paddingZero">
                            <img src="semestralka/img/books/<?= $history->getPicture() ?>" class="img-thumbnail itemIcon w-100 h-100" alt="kniha">
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 paddingZero">
                            <div class="card middlePart">
                                <div class="card-body myCard">
                                    <h4> <?= $history->getName() ?> </h4>
                                    <h6 class="card-subtitle mb-2 text-muted autor">
                                        <?= $history->getAuthorName()." ".$history->getAuthorSurname() ?>, <time datetime="<?= $history->getReleaseYear() ?>"><?= $history->getReleaseYear() ?></time>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 paddingZero">
                            <div class="card">
                                <div class="card-body myCard">
                                    <p>
                                        <strong>Rezervované:</strong> <br>
                                        <time datetime="<?= $history->getRequestDate() ?>"><?= $history->getRequestDateFormatted() ?></time>
                                    </p>
                                    <p>
                                        <strong>Vyzdvihnuté:</strong> <br>
                                        <?php if (is_null($history->getReserveDay())) { ?>
                                            <span><?= $history->getReserveDayFormatted() ?></span>
                                        <?php } else { ?>
                                            <time datetime="<?= $history->getReserveDay() ?>"><?= $history->getReserveDayFormatted() ?></time>
                                        <?php } ?>
                                    </p>
                                    <p>
                                        <strong>Vrátené:</strong> <br>
                                        <?php if (is_null($history->getReturnDay())) { ?>
                                            <span><?= $history->getReturnDayFormatted() ?></span>
                                        <?php } else { ?>
                                            <time datetime="<?= $history->getReturnDay() ?>"><?= $history->getReturnDayFormatted() ?></time>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }} ?>

        <?php if (!empty($data['history'])) { ?>
        <nav aria-label="Hladanie v historii">
            <ul class="pagination justify-content-center mt-3">
                <?php if (!is_null($data)) { ?>
                    <?= $data['paginator']->getLayout($_GET['page']) ?>
                <?php }?>
            </ul>
        </nav>
        <?php } ?>
    </div>
</div>