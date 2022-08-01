<?php
session_start();
require "database.php";

$error = null;
$ok =false;

if(!isset($_SESSION["user"])){
    header("Location: login.php");
}
$user=$_SESSION["user"];
//ESTRAGGO LE INFORMAZIONI DELL'UTENTE LOGGATO
$risultato = mysqli_query($connessioneDB,"SELECT * FROM utenti WHERE utenti.user='$user'");

$utente = mysqli_fetch_assoc($risultato);

$user=$utente["user"];
$nome = $utente["nome"];
$cognome = $utente["cognome"];
$bio = $utente["bio"];
$immagine = $utente["immagine"];
$pass= $utente["password"];

//DICHIARO FUNZIONE PER AGGIORNARE GLI ATTRIBUTI DELL'UTENTE
function aggiorna($connessioneDB){

    global $user,$nome,$cognome,$bio,$immagine,$pass,$ok,$error;
$preuser = $_SESSION["user"];

$risultato=mysqli_query($connessioneDB,"UPDATE utenti SET user='$user',nome='$nome',cognome='$cognome',bio='$bio',immagine='$immagine',password='$pass' WHERE utenti.user='$preuser'");


if(!$risultato){
    $error=mysqli_error($connessioneDB);

}
    if($error==null){
        $_SESSION["user"]=$user;
    $ok=true;}
}
//VERIFICO CHE L'UTENTE HA CLICCATO IL TASTO DI SALVATAGGIO MODIFICHE DELLA PASSWORD
if(isset($_POST["passch"])){
//VERIFICO SE LA VECCHIA PASSWORD CORRISPONDE ALL'ATTUALE PASSWORD
if($_POST["pre-pass"]!=$pass){
    $error="Vecchia password non inserita correttamente";
}

else{
$pass=$_POST["new-pass"];
aggiorna($connessioneDB);
}

}

//VERIFICO CHE L'UTENTE HA CLICCATO IL TASTO DI SALVATAGGIO MODIFICE DEL INFORMAZIONI PERSONALI
if(isset($_POST["userinfo"])){
    //VERIFICO SEI I CAMPI SONO VUOTI
    if(empty($_POST["nome"]) or empty($_POST["cognome"]) or empty($_POST["user"])){

        $error="Riempire tutti i campi";
    }
    else{
        //VERIFICO SE è STATO MODIFICATO IL VALORE DEL USERNAME
        if($_POST["user"]!=$user){

            $tempuser = $_POST["user"];
            //VERIFICO SE IL NUOVO USERNAME E' GIA PRESENTE ALL'INTERNO DEL DATABASE
            $risultato=mysqli_query($connessioneDB,"SELECT utenti.user FROM utenti WHERE utenti.user='$tempuser'");
            if(mysqli_num_rows($risultato)==0){
                $user=$tempuser;
            }
            else{
                $error="Username già preso";
            }

        }
        //INSERISCO VALORI DEL FORM NELLE VARIABILE E RICHIAMO LA FUNZIONA AGGIORNA PASSANDO LA CONNESSIONE AL DB
        $nome=$_POST["nome"];
        $cognome=$_POST["cognome"];
        $bio =$_POST["bio"];
        aggiorna($connessioneDB);

    }

}
//VERIFICO CHE L'UTENTE HA CLICCATO IL TASTO DI SALVATAGGIO MODIFICE DEL IMMAGINE PROFILO

if(isset($_POST["fotoch"])){

    function caricaimaggine($user){
        $currentDirectory = getcwd();
        $uploadDirectory = "uploads/";
        $fileName = $_FILES['the_file']['name'];
        $fileTmpName  = $_FILES['the_file']['tmp_name'];
        $tmp = explode('.', $fileName);
        $estenzioneFile = end($tmp);
        $posizione = $uploadDirectory . basename($user.".".$estenzioneFile);
        $posizioneEstesa = $currentDirectory ."/".$posizione;

        $esitoUpload = move_uploaded_file($fileTmpName, $posizioneEstesa);

        if ($esitoUpload) {
            return $posizione;
        } else {
            return "assets/img/user.svg";
        }

    }

    $immagine=caricaimaggine($user);
    aggiorna($connessioneDB);


}


?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profilo - Chat</title>
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body  class="p-5">
<div id="success_tic" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <a class="close" href="#" data-dismiss="modal">&times;</a>
            <div class="page-body">
                <div class="head">
                    <h3 style="margin-top:5px;">Dati aggiornati correttamente</h3>

                </div>

                <h1 style="text-align:center;"><div class="checkmark-circle m-3">
                        <div class="background"></div>
                        <div class="checkmark draw"></div>

                    </div><h1>
                        <div class="text-center">
                            <a href="#" data-dismiss="modal" class="btn btn-outline-info">Ritorna</a>
                        </div>
            </div>
        </div>
    </div>

</div>

<?php

//SETTO I VALORI DEL FORM CON L'INFORMAZIONI ESISTENTI DELL'UTENTE
$pagina = <<<pagina

<div class="container">
                <h3 class="text-dark mb-4">Profilo</h3>
                <p class="text-danger mb-4">{$error}</p>
                <div class="row mb-3">
                    <!--Card foto prifilo !-->
                    <div class="col">
                        <form method="post" action="profile.php" enctype="multipart/form-data">
                        <div class="card mb-3"><div class="card-body text-center shadow"><img id="image" class="profile-pic rounded-circle mb-3 mt-4" src="{$immagine}" width="200" height="200" />
                            <div class="mb-3"><input type="file" id="inputfile" class="file-upload btn btn-dark btn-sm" accept="image/*" name="the_file"></div>
                            <button type="submit" class="btn btn-primary" name="fotoch">Salva</button>
                        </div>
                        </div>
                        </form>
                    </div>
                    <!-- Fine Card foto prifilo !-->

                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col">
                                <div class="card shadow mb-3">
                                    <div class="card-header py-3">
                                        <p class="text-primary m-0 font-weight-bold">Impostazioni utente</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="profile.php" method="post">
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label ><strong>Username</strong></label><input class="form-control" type="text" value="{$user}" name="user" required></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group"><label ><strong>Bio</strong></label><input class="form-control" type="text"  name="bio" value="{$bio}" required></div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label ><strong>First Name</strong></label><input class="form-control" type="text" value="{$nome}" required name="nome"></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group"><label ><strong>Last Name</strong></label><input class="form-control" type="text" value="{$cognome}" required name="cognome"></div>
                                                </div>
                                            </div>
                                            <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="userinfo">Salva</button></div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <p class="text-primary m-0 font-weight-bold">Cambia Password</p>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="profile.php">
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="form-group"><label><strong>Vecchia password</strong></label><input class="form-control" type="password" name="pre-pass" required></div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group"><label ><strong>Nuova password</strong></label><input class="form-control" type="password"  name="new-pass" required></div>
                                                </div>
                                            </div>
                                            <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="passch">Salva</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


pagina;


echo $pagina;

?>


    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="assets/js/theme.js"></script>
<script>
    $(document).ready(function() {
        // dichiariazione della funzione come
        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
//aggiunta attributo Src contenete l'url dell immagine a gli elementi di classe profile-pic
                reader.onload = function (e) {
                    $('.profile-pic').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
            else {
                $('.profile-pic').attr('src', "assets/img/user.svg");
            }
        };

//Creazione evento cambiamento dei tag con classe file-upload dove viene passato alla funzione readURL viene passato l'intero oggetto
        $(".file-upload").on('change', function(){
            readURL(this);
        });

    });
</script>

<?php
//MOSTRO MODAL DI SUCCESSO
if($ok){
    echo "<script type='text/javascript'>
$(document).ready(function(){
$('#success_tic').modal('show');
});
</script>";
}


?>
</body>

</html>