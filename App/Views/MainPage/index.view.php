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

<?php if (isset($_SESSION['user']) && $_SESSION['user']->getPermissions() == 'A' ) { ?>
    <div class="container">
        <h4><a href="semestralka?c=MainPage&a=add" class="adminControls"> <i class="fas fa-plus"></i> Pridaj novú </a></h4>
    </div>
<?php } ?>

<div class="container infoCards">
    <div class="row">
        <?php /** @var \App\Models\News[] $data */ ?>
        <?php if (is_null($data)) { ?>
            <h4> Stranka neexistuje </h4>
        <?php } else { ?>
        <?php foreach ($data['news'] as $info) { ?>
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
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']->getPermissions() == 'A' ) { ?>
                            <span class="float-left m-2">
                                <a href="" class="adminControls"> <i class="fas fa-pen ml-2"></i> <span class="hideWhenSmall">Upraviť</span> </a>
                                <a href="semestralka?c=MainPage&a=delete&id=<?= $info->getId(); ?>" class="adminControls"> <i class="fas fa-trash ml-2"></i> <span class="hideWhenSmall">Vymazať</span> </a>
                            </span>
                        <?php } ?>
                        <small class="text-muted float-right m-2">
                            <time datetime="<?= $info->getCreationDate() ?>"><?= $info->getCreationDate() ?></time>
                        </small>
                    </footer>
                </div>
            </div>
        <?php }} ?>


    </div>
</div>

<nav aria-label="Hladanie medzi novinkami">
    <ul class="pagination justify-content-center">
        <?php if (!is_null($data)) { ?>
            <?php if ($_GET['page'] <= 1) { ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
            <?php } else { ?>
                <li class="page-item">
                    <a class="page-link" href="semestralka?c=MainPage&page=<?= $_GET['page'] - 1 ?>">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
            <?php } ?>

            <?php for ($i=1; $i <= $data['numberOfNews']; $i++) { ?>
                <li class="page-item">
                    <a class="page-link" href="semestralka?c=MainPage&page=<?= $i ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php } ?>

            <?php if ($_GET['page'] >= ($data['numberOfNews'])) { ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </li>
            <?php } else { ?>
                <li class="page-item">
                    <a class="page-link" href="semestralka?c=MainPage&page=<?= $_GET['page'] + 1 ?>">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </li>
        <?php }} ?>
    </ul>
</nav>
