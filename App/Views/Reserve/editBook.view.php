<title>Úprava knihy</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
</head>

<body>

<?php include "App/Views/Navbar/Navbar.view.php"; ?>

<div class="container text-center">
    <h1 class="display-4 mb-3 mt-3"> Úprava knihy </h1>
</div>

<?php /** @var $data[] */ ?>
<div class="col-md-6 col-sm-12 col-xs-12 mb-4 container">
    <div class="card">
        <form action="semestralka?c=Reserve&a=editBook&ISBN=<?= $data['data']['ISBN']; ?>" method="post" enctype="multipart/form-data">
            <div class="card-body">

                <div class="text-center">
                    <img src="semestralka/img/books/<?= $data['data']['file'] ?>" alt="obrazok" class="myEditBookIMG">
                </div>
                <?php include "App/Views/Reserve/Common/form.view.php"; ?>

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
                    <button type="submit" class="container-fluid submitButton">Upraviť</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?php
