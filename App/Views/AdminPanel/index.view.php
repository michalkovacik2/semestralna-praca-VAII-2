<title>Admin panel</title>
<link rel="stylesheet" href="semestralka/css/main_page.css">
<link rel="stylesheet" href="semestralka/css/adminPanel_page.css">
<script src="semestralka/js/adminPanel.js"></script>
<script src="semestralka/js/paginator.js"></script>
<script src="semestralka/js/InfoPopUp.js"></script>
</head>
<body>
<div id="banner">
    <?php include "App/Views/Navbar/Navbar.view.php"; ?>

    <div class="jumbotron jumbotron-fluid  bg-transparent">
        <div class="container">
            <h1 class="display-2 bannerText">Admin Panel</h1>
        </div>
    </div>
</div>

<?php /** @var $data array */ ?>
<?php /** @var \App\Core\AAuthenticator $auth */ ?>

<div class="container mt-5 mb-5">

    <div class="input-group">
        <input type="text" class="form-control outlineButton" placeholder="Vyhľadanie" aria-label="Vyhľadanie" aria-describedby="searchButton" id="searchBar">
        <div class="input-group-append">
            <button class="btn orangeFont outlineButton" type="button" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table" id="adminPanelTable">
        </table>
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

