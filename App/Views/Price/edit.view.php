<title>Pridanie poplatku</title>
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

<div class="container mt-5">
    <div class="table-responsive">
        <form action="semestralka?c=Price&a=edit&id=<?= $data['data']['id']; ?>" method="post" enctype="multipart/form-data">
            <table class="table" id="priceTableDisabled">
                <?php include "Common/form.view.php"; ?>
                <td>
                    <button type="submit" class="container-fluid tableButton">Upraviť</button>
                </td>
                </tr>
            </table>
    </div>
</div>



