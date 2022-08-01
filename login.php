<?php
session_start();
//IMPORTO FILE PER LA CONNESSIONE AL DATABSE
require "database.php";
//DEFINISCO VARIABILE PER SALVARE EVENTUALI ERRORI
$error = null;
//VERIFICO SE ESISTE LA SESSIONE
if(isset($_SESSION["user"])){
    //SE LA SESSIONE NON ESISTE L'UTENTE VIENE REINDIRIZZATO ALLA PAGINA DI LOGIN
    header("Location: index.php");
}


//VERIFICO SE IL PULSANTE DI INVIO DEL FORM E STATO SETTATO
if(isset($_POST["vai"])){
    //SALVO I CONTENUTO DEL FORM NELLE VARIBILI
    $user = $_POST["user"];
    $pass=$_POST["password"];
//VERIFICO CHE I CAMPI NON SIANO VUOTI
    if(empty($user) or empty($pass)){
        $error="Riempire tutti i campi";
    }
    else{
        //ESEGUO QUERY PER VERIFICARE CHE LA COMBINAZIONE UTENTE PASSWORD SIANO CORRETTE
        $risultato = mysqli_query($connessioneDB, "SELECT * from utenti where utenti.user = '$user' and utenti.password='$pass'");
//SE IL NUMERO DEI RECORD DATO DAL RISULTATO DELLA QUERY E' UGUALE A 0 VIENE ISTITUITO L'ERRORE
        if (mysqli_num_rows($risultato)==0) {
            $error = "Utente o password errati";
        } else {
            //SE IL NUMERO DEI RECORD E' DIVERSO DA 0 INIZZIALIZZO LA SESSIONE CONTENTE L' USERNAME E RIPORTO L'UTENTE NELLA PAGINA PRINCIPALE
            $_SESSION["user"] = $user;
            header("Location: index.php");
        }

    }

}

?>



<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Chat</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body class="bg-gradient-dark">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-12 col-xl-10">
            <div class="card shadow-lg o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-flex">
                            <div class="flex-grow-1 bg-login-image" style="background-image: url(assets/img/login.gif);"></div>
                            </div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h4 class="text-dark mb-4">Ben tornato!</h4>
                                    <?php
                                    if($error!=null){
                                        echo "<p class='text-danger'>".$error."</p>";
                                    }
                                    ?>
                                </div>
                                <form class="user" method="post" action="login.php">
                                    <div class="form-group"><input class="form-control form-control-user" type="text" placeholder="Username" name="user" required></div>
                                    <div class="form-group"><input class="form-control form-control-user" type="password" placeholder="Password" name="password" required></div>
                                    <div class="form-group">
                                    </div><button class="btn btn-primary btn-block text-white btn-user" type="submit" name="vai">Login</button>
                                    <hr>
                                </form>
                                <div class="text-center"><a class="small" href="register.php">Crea un Account!</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
<script src="assets/js/theme.js"></script>
</body>

</html>