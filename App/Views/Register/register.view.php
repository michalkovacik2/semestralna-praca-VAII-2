<?php /** @var $data[] */ ?>
<title>Registrácia</title>
<link rel="stylesheet" href="semestralka/css/login_page.css">
<link rel="stylesheet" href="semestralka/css/register_page.css">
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4 col-sm-8 col-12 offsetFromTop">
            <div class = "text-center">
                <a href="semestralka?c=MainPage">
                    <img class="text-center bgColor bookImg" src="semestralka/img/bookSmall.png" alt="Obrázok knihy">
                </a>
            </div>
            <!-- Formular -->
            <div class="container bgColor" id="windowRegister">
                <a href="semestralka?c=Login" class="backIcon">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <h2 class="text-center" id="loginLabel">Registrácia</h2>
                <?php if (is_null($data))  { ?>
                    <div class="alert alert-success" role="alert">
                        Váš účet bol úspešne vytvorený, teraz môžete pokračovať na
                        <a href="semestralka?c=Login">prihlásenie</a>.
                    </div>
                <?php } ?>
                <form action="semestralka?c=Register&a=register" class="mt-0 ml-5 mr-5 mb-2" method="post">
                    <div class="form-group">
                        <label for="idMeno" class="font-weight-bold">Meno</label>
                        <input type="text" class="form-control" id="idMeno" name="name" placeholder="Meno" value="<?= is_null($data) ? "" : $data['data']['name'] ?>">
                    </div>
                    <?php if (!is_null($data) && isset($data['errors']['name'])) {
                        foreach ($data['errors']['name'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                    <?php }
                    }?>
                    <div class="form-group">
                        <label for="idPriezvisko" class="font-weight-bold">Priezvisko</label>
                        <input type="text" class="form-control" id="idPriezvisko" placeholder="Priezvisko" name="surname" value="<?= is_null($data) ? "" : $data['data']['surname'] ?>">
                    </div>
                    <?php if (!is_null($data) && isset($data['errors']['surname'])) {
                        foreach ($data['errors']['surname'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                    <div class="form-group">
                        <label for="idEmail" class="font-weight-bold">Email</label>
                        <input type="email" class="form-control" id="idEmail" placeholder="Emailová adresa" name="email" value="<?= is_null($data) ? "" : $data['data']['email'] ?>">
                    </div>
                    <?php if (!is_null($data) && isset($data['errors']['email'])) {
                        foreach ($data['errors']['email'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                    <div class="form-group">
                        <label for="idCislo" class="font-weight-bold">Telefónne číslo</label>
                        <input type="tel" class="form-control" id="idCislo" placeholder="formát +421" name="phone" value="<?= is_null($data) ? "" : $data['data']['phone'] ?>">
                    </div>
                    <?php if (!is_null($data) && isset($data['errors']['phone'])) {
                        foreach ($data['errors']['phone'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                    <div class="form-group">
                        <label for="idHeslo" class="font-weight-bold">Heslo</label>
                        <input type="password" class="form-control" id="idHeslo" placeholder="Heslo" name="password" value="<?= is_null($data) ? "" : $data['data']['password'] ?>">
                    </div>
                    <?php if (!is_null($data) && isset($data['errors']['password'])) {
                        foreach ($data['errors']['password'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                    <div class="form-group">
                        <label for="idHeslo2" class="font-weight-bold">Zopakujte heslo</label>
                        <input type="password" class="form-control" id="idHeslo2" placeholder="Heslo" name="password2" value="<?= is_null($data) ? "" : $data['data']['password2'] ?>">
                    </div>
                    <?php if (!is_null($data) && isset($data['errors']['password2'])) {
                        foreach ($data['errors']['password2'] as $e) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $e ?>
                            </div>
                        <?php }
                    }?>
                    <div class="text-center">
                        <button type="submit" class="myButton">Registrovať sa</button>
                    </div>

                    <div id="underFormText">
                        <p>
                            Späť na <a href="semestralka?c=Login"> <strong>prihlásenie</strong> </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
