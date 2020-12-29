<title>Rezervácia</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
<link rel="stylesheet" href="semestralka/css/reserve_page.css">
<script src="semestralka/js/reserve.js"></script>
<script src="semestralka/js/paginator.js"></script>
<script src="semestralka/js/InfoPopUp.js"></script>
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
                    </div>
                </div>
            </div>

            <nav aria-label="Hladanie medzi knihami" id="paginator"></nav>
        </div>

        <form action="semestralka?c=Reserve&a=editBook" method="get" class="d-none" id="editBookFormID">
            <input type="text" name="c" value="Reserve">
            <input type="text" name="a" value="editBook">
            <input type="text" name="ISBN" value="" id="editBookFormISBN">
        </form>

        <div class="modal" id="modalGenre">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalGenreTitle"></h2>
                        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>
                    </div>
                        <div class="modal-body text-left">
                            <label for="modalGenreName" class="font-weight-bold">Názov žánru: </label>
                            <input type="text" class="form-control" id="modalGenreName" name="name" placeholder="Žáner">
                            <div class="mt-1 alert alert-danger d-none" id="modalGenreErrors">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success" id="modalGenreButton"></button>
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>
