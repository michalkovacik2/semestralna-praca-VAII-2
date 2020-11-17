<title>Prihlásenie</title>
<link rel="stylesheet" href="semestralka/css/login_page.css">
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4 col-sm-8 col-12 offsetFromTop">
            <div class = "text-center">
                <a href="semestralka?c=MainPage">
                    <img class="text-center bgColor bookImg" src="semestralka/img/bookSmall.png" alt="Obrázok knihy">
                </a>
            </div>
            <!-- Formular -->
            <div class="container bgColor" id="windowLogin">
                <a href="semestralka?c=MainPage" class="backIcon">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <h2 class="text-center" id="loginLabel">Prihlásenie</h2>

                <form action="#" class="mt-0 ml-5 mr-5 mb-2">
                    <div class="form-group">
                        <label for="idEmail" class="font-weight-bold">Email</label>
                        <input type="email" class="form-control" id="idEmail" placeholder="Emailová adresa" name="email">
                    </div>
                    <div class="form-group">
                        <label for="idHeslo" class="font-weight-bold">Heslo</label>
                        <input type="password" class="form-control" id="idHeslo" placeholder="Heslo" name="password">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="myButton">Prihlásiť</button>
                    </div>

                    <div id="underFormText">
                        <p>
                            Nemáte účet, tak sa <a href="semestralka?c=Register"> <strong>zaregistrujte</strong> </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

