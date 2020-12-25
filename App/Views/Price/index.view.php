<title>Cenník</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
<link rel="stylesheet" href="semestralka/css/price_page.css">
</head>
<body>
<div id="banner">
    <?php include "App/Views/Navbar/Navbar.view.php"; ?>

    <div class="jumbotron jumbotron-fluid  bg-transparent">
        <div class="container">
            <h1 class="display-2 bannerText">Cenník</h1>
        </div>
    </div>
</div>

<?php /** @var $data array */ ?>
<?php /** @var \App\Core\AAuthenticator $auth */ ?>

<div class="container mt-5 mb-5">

    <?php /** @var \App\Core\AAuthenticator $auth */ ?>
    <?php if ($auth->isLogged() && $auth->hasPrivileges()) { ?>
            <h4><a href="semestralka?c=Price&a=add" class="adminControls"> <i class="fas fa-plus"></i> Pridaj nový poplatok </a></h4>
    <?php } ?>

    <div class="table-responsive">
        <table class="table" id="priceTable">
            <tr>
                <th>Druh poplatku</th>
                <th>Cena</th>
                <?php if ($auth->isLogged() && $auth->hasPrivileges()) { ?>
                    <th class="text-right">Úpravy</th>
                <?php } ?>
            </tr>
            <?php foreach ($data['data'] as $item) { ?>
                <?php /** @var $item \App\Models\Price_list */ ?>
                <tr>
                    <td><?= $item->getName() ?></td>
                    <td><?= $item->getPrice() ?> €</td>
                    <?php if ($auth->isLogged() && $auth->hasPrivileges()) { ?>
                        <td class="text-right">
                            <a href="semestralka?c=Price&a=edit&id=<?= $item->getId(); ?>" class="adminControls"> <i class="fas fa-pen ml-2"></i> <span class="hideWhenSmall">Upraviť</span> </a>
                            <span class="adminControls" data-toggle="modal" data-target="#myModal<?= $item->getId(); ?>"> <i class="fas fa-trash ml-2"></i> <span class="hideWhenSmall">Vymazať</span> </span>
                            <div class="modal" id="myModal<?= $item->getId(); ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h2 class="modal-title">Odstránenie poplatku</h2>
                                            <button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                        </div>

                                        <div class="modal-body text-left">
                                            Naozaj si prajete odstrániť tento poplatok: <br>
                                            Druh poplatku: <b> <?= $item->getName(); ?> </b> <br>
                                            Cena: <b> <?= $item->getPrice(); ?> </b> € <br>
                                            Po zmazaní budú údaje stratené.
                                        </div>

                                        <div class="modal-footer">
                                            <a href="semestralka?c=Price&a=delete&id=<?= $item->getId(); ?>" class="btn btn-danger">Zmazať</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

