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

<?php /** @var \App\Core\AAuthenticator $auth */ ?>
<?php if ($auth->isLogged() && $auth->hasPrivileges()) { ?>
    <div class="container">
        <h4><a href="semestralka?c=MainPage&a=add" class="adminControls"> <i class="fas fa-plus"></i> Pridaj novú </a></h4>
    </div>
<?php } ?>

<div class="container infoCards">
    <div class="row">
        <?php /** @var \App\Models\News[] $data */ ?>
        <?php foreach ($data['news'] as $info) { ?>
            <?php /** @var \App\Models\News $info */ ?>
            <div class="col-md-6 col-sm-12 col-xs-12 mb-4">
                <div class="card">
                    <img src="data:image/png;base64,<?= $info->getPicture() ?>" class="card-img-top" alt="obrazok pre novinky">
                    <div class="card-body">
                        <h2 class="card-title"><?= $info->getTitle() ?></h2>
                        <p class="card-text">
                            <?= $info->getText() ?>
                        </p>
                    </div>
                    <footer>
                        <?php if ($auth->isLogged() && $auth->hasPrivileges() ) { ?>
                            <span class="float-left m-2">
                                <a href="semestralka?c=MainPage&a=edit&id=<?= $info->getId(); ?>&page=<?= $_GET['page']; ?>" class="adminControls"> <i class="fas fa-pen ml-2"></i> <span class="hideWhenSmall">Upraviť</span> </a>
                                <span class="adminControls" data-toggle="modal" data-target="#myModal<?= $info->getId(); ?>"> <i class="fas fa-trash ml-2"></i> <span class="hideWhenSmall">Vymazať</span> </span>
                            </span>

                            <div class="modal" id="myModal<?= $info->getId(); ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h2 class="modal-title">Odstránenie novinky</h2>
                                            <button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                        </div>

                                        <div class="modal-body">
                                            Naozaj si prajete odstrániť túto novinku. Po zmazaní budú údaje stratené.
                                        </div>

                                        <div class="modal-footer">
                                            <a href="semestralka?c=MainPage&a=delete&page=<?= $_GET['page']; ?>&numElements=<?= count($data['news']); ?>&id=<?= $info->getId(); ?>" class="btn btn-danger">Zmazať</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <small class="text-muted float-right m-2">
                            <time datetime="<?= $info->getCreationDate() ?>"><?= $info->getCreationDateFormated() ?></time>
                        </small>
                    </footer>
                </div>
            </div>
        <?php } ?>


    </div>
</div>

<nav aria-label="Hladanie medzi novinkami">
    <ul class="pagination justify-content-center">
        <?php if (!is_null($data)) { ?>
            <?= $data['paginator']->getLayout($_GET['page']) ?>
        <?php }?>
    </ul>
</nav>
