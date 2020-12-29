<title>Pridanie knihy</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
</head>

<body>

<?php include "App/Views/Navbar/Navbar.view.php"; ?>

<div class="container text-center">
    <h1 class="display-4 mb-3 mt-3"> Pridanie knihy </h1>
</div>

<?php /** @var $data[] */ ?>
<div class="col-md-6 col-sm-12 col-xs-12 mb-4 container">
    <div class="card">
        <form action="semestralka?c=Reserve&a=addBook" method="post" enctype="multipart/form-data">
            <div class="card-body">

                <p class="card-text">
                    <label for="idISBN" class="font-weight-bold">ISBN</label>
                    <input type="text" class="form-control outlineButton colorBlack" name="ISBN" id="idISBN" placeholder="ISBN" value="<?= is_null($data) || !isset($data['data']) ? "" : $data['data']['ISBN'] ?>">
                </p>
                <?php if (!is_null($data) && isset($data['errors']['ISBN'])) {
                    foreach ($data['errors']['ISBN'] as $e) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e ?>
                        </div>
                    <?php }
                }?>

                <?php include "App/Views/Reserve/Common/form.view.php"; ?>

                <p class="card-text">
                    <label for="idAmount" class="font-weight-bold">Počet kníh</label>
                    <input type="number" class="form-control outlineButton colorBlack" name="amount" id="idAmount" min="1" step="1" value="<?= is_null($data) || !isset($data['data']) ? "1" : $data['data']['amount'] ?>">
                </p>
                <?php if (!is_null($data) && isset($data['errors']['amount'])) {
                    foreach ($data['errors']['amount'] as $e) { ?>
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


