<?php /** @var $data[] */ ?>
<title>Hlavná stránka</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
</head>

<body>

<?php include "App/Views/Navbar/Navbar.view.php"; ?>

<div class="container text-center">
    <h1 class="display-4 mb-3 mt-3"> Pridanie novinky </h1>
</div>

<div class="col-md-6 col-sm-12 col-xs-12 mb-4 container">
    <div class="card">
        <form action="semestralka?c=MainPage&a=add" method="post" enctype="multipart/form-data">
            <div class="card-body">
                <div class="card-title form-group">
                    <label for="idTitle" class="font-weight-bold">Nadpis</label>
                    <input type="text" class="form-control outlineButton" id="idTitle" name="title" placeholder="Nadpis novinky" value="<?= is_null($data) ? "" : $data['data']['title'] ?>">
                </div>
                <?php if (!is_null($data) && isset($data['errors']['title'])) {
                    foreach ($data['errors']['title'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>
                <p class="card-text">
                    <label for="idText" class="font-weight-bold">Text</label>
                    <textarea class="form-control outlineButton" name="text" id="idText" cols="50" rows="10" placeholder="Sem napiste text novinky"><?= is_null($data) ? "" : $data['data']['text'] ?></textarea>
                </p>
                <?php if (!is_null($data) && isset($data['errors']['text'])) {
                    foreach ($data['errors']['text'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>
                <div>
                    <label for="idFile">
                        <i class="fas fa-image"></i> Nahrajte obrázok
                    </label>
                </div>
                <div>
                    <input type='file' name='file' id="idFile" accept="image/png, image/jpeg">
                </div>
                <?php if (!is_null($data) && isset($data['errors']['file'])) {
                    foreach ($data['errors']['file'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>

                <div class="container mt-5">
                    <button type="submit" class="container-fluid submitButton">Pridať</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!--<div class="container">-->
<!--    <form action="#" method="post">-->
<!--        <div class="form-group">-->
<!--            <label for="idAdd" class="font-weight-bold">Nadpis</label>-->
<!--            <input type="text" class="form-control" id="idAdd" name="add">-->
<!--        </div>-->
<!--    </form>-->
<!--</div>-->



