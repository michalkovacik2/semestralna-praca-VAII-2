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


<div class="container mt-5 mb-5">
    <h1>Cenník platný do <time datetime="2021-12-31">31.12.2021</time></h1>

    <div class="table-responsive mt-5">
        <table class="table table-bordered">

            <thead class="mainHeader">
            <tr>
                <th>Druh poplatku</th>
                <th>Cena</th>
            </tr>
            </thead>

            <tbody class="tabBody">
            <tr>
                <th colspan="2" class="categoryHeader">Výška ročného členského poplatku</th>
            </tr>

            <tr>
                <td>Deti mladšie ako 6 rokov</td>
                <td>Zadarmo</td>
            </tr>
            <tr>
                <td>Deti a mládež mladšia ako 15 rokov</td>
                <td>2 €</td>
            </tr>
            <tr>
                <td>Študenti stredných a vysokých škôl</td>
                <td>3 €</td>
            </tr>
            <tr>
                <td>Dospelý mladší ako 65 rokov</td>
                <td>7 €</td>
            </tr>
            <tr>
                <td>ZŤP a darcovia krvi</td>
                <td>Zadarmo</td>
            </tr>

            <tr>
                <th colspan="2" class="categoryHeader">Sankčné poplatky</th>
            </tr>
            <tr>
                <td>Oneskorenie vrátenia knihy o 1.týždeň</td>
                <td>1 €</td>
            </tr>
            <tr>
                <td>Oneskorenie vrátenia knihy o 2.týždne</td>
                <td>3 €</td>
            </tr>
            <tr>
                <td>Oneskorenie vrátenia knihy o 3.týždne</td>
                <td>5 €</td>
            </tr>
            <tr>
                <td>Poškodenie knihy</td>
                <td>2 €</td>
            </tr>
            <tr>
                <td>Poškodenie majetkového vybavenia knižnice</td>
                <td>20 € + (náklady na opravu)</td>
            </tr>

            <tr>
                <th colspan="2" class="categoryHeader">Ostatné služby</th>
            </tr>
            <tr>
                <td>Kopírovanie 1 strana A4</td>
                <td>0,15 €</td>
            </tr>
            <tr>
                <td>Kopírovanie 1 strana A4 obojstranne</td>
                <td>0,20 €</td>
            </tr>
            <tr>
                <td>Kopírovanie 1 strana s obrázkom</td>
                <td>0,30 €</td>
            </tr>
            <tr>
                <td>Použitie počítača 30 minút denne</td>
                <td>Zadarmo</td>
            </tr>
            <tr>
                <td>Použitie počítača každých dalších začatých 30 minút</td>
                <td>0,50 €</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

