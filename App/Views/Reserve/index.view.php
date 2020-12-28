<title>Rezervácia</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
<link rel="stylesheet" href="semestralka/css/reserve_page.css">
<script src="semestralka/js/reserve.js"></script>
</head>
<body>

<?php include "App/Views/Navbar/Navbar.view.php"; ?>
<?php /** @var $data[] */ ?>

<div class="container">
    <div class="row">

        <div class="col-md-4 col-sm-12 mt-4">
            <?php /** @var \App\Core\AAuthenticator $auth */ ?>
            <?php if ($auth->isLogged() && $auth->hasPrivileges()) { ?>
                <div>
                    <h4><a href="semestralka?c=Reserve&a=addBook" class="adminControls"> <i class="fas fa-plus"></i> Pridaj novú knihu </a></h4>
                </div>
            <?php } ?>
            <ul class="list-group" id="genres">
            </ul>
        </div>

        <!-- prava strana -->
        <div class="col-md-8 col-sm-12 mt-4">
            <div class="input-group">
                <input type="text" class="form-control outlineButton" placeholder="Vyhľadanie" aria-label="Vyhľadanie" aria-describedby="searchButton" id="searchBar">
                <div class="input-group-append">
                    <button class="btn orangeFont outlineButton" type="button" id="searchButton"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <!-- Koniec search baru -->

            <!-- Knihy -->
            <div id="books">
            </div>

            <div id="modal" class="modal modal-message modal-success fade" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center"  id="modalIcon">
                        </div>
                        <div class="modal-body text-center" id="modalTEXT"></div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn" data-dismiss="modal" id="modalButton">OK</button>
                        </div>
                    </div> <!-- / .modal-content -->
                </div> <!-- / .modal-dialog -->
            </div>

            <nav aria-label="Hladanie medzi knihami" id="paginator"></nav>
        </div>
    </div>
</div>
