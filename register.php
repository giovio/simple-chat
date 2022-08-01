
<?php
require "database.php";
$error=null;
$ok=false;

//DICHIARO FUNZIONE PER FARE L'UPLOAD DELL'IMMAGINE PROFILO DOVE PASSO IL NOME UTENTE
function caricaimaggine($user){
   //DICHIARO LE VARIE VARIABILI COME IL NOME DEL FILE E IL PERCORSO DI SALVATAGGIO
    $currentDirectory = getcwd();
    $uploadDirectory = "uploads/";
    $fileName = $_FILES['the_file']['name'];
    $fileTmpName  = $_FILES['the_file']['tmp_name'];
    $tmp = explode('.', $fileName);
    $estenzioneFile = end($tmp);
    $posizione = $uploadDirectory . basename($user.".".$estenzioneFile);
    $posizioneEstesa = $currentDirectory ."/".$posizione;

    $esitoUpload = move_uploaded_file($fileTmpName, $posizioneEstesa);
//VERIFICO SE L'IMMAGINE E' STATA CARICATA
    if ($esitoUpload) {
        //SE E' STATA CARICATA MI RITORNI LA POSIZIONE
        return $posizione;
    } else {
        //SE NON E' STATA CARICATA MI RITORNI LA POSIZIONE DELL'IMMAGINE DI DEFAULT
        return "assets/img/user.svg";
    }

}
//VERIFICO SE L'UTENE HA CLICCATO IL TASTO REGISTRATI
if (isset($_POST['submit'])) {
//SALVO IL CONTENUTO DEL FORM NELLE VARIABILI
    $user=$_POST["user"];
    $nome=$_POST["nome"];
    $pass=$_POST["password"];
    $cognome=$_POST["cognome"];
    $bio =$_POST["stato"];
//VERIFICO SE I CAMPI SONO VUOTI
    if(empty($user) or empty($pass) or  empty($nome) or empty($cognome)){
        $error="Riempire tutti i campi";

    }
    else{

        //VERIFICO SE IL NOME UTENTE SCELTO E' STATO GIà PRESO
        $risultato=mysqli_query($connessioneDB,"SELECT utenti.user FROM utenti WHERE utenti.user='$user'");
        if(mysqli_num_rows($risultato)>0){
            $error="Utente già presente";
        }
        //VERIFICO LA PRESENZA DI ERRORI
        if($error==null){
            //SE NON CI SONO ERRORI RICHIAMO LA FUNZIONE DI UPLOAD E CONSERVO IL RITORNO NELLA VARIBILE ED ESEGUO LA QUERY
            $immagine = caricaimaggine($user);
            $risultato=mysqli_query($connessioneDB,"INSERT INTO utenti VALUES ('$user','$nome','$cognome','$bio','$immagine','$pass')");
            if(!$risultato){
                //SE LA QUERY NON VIENE ESEGUITA ELIMINO L'IMMAGINE PRECEDENTEMENTE CARICATA
                $error="Errore della query".mysqli_error($connessioneDB);
                unlink(getcwd().$immagine);
            }
            else{
                //IMPOSTO VARIABILE PER FAR MOSTRARE IL MODAL DI SUCCESSO
                $ok=true;
            }
        }
    }
}



?>







<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register - Chat</title>
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">



</head>






<body class="bg-gradient-dark">


<!-- Modal succes -->
<div id="success_tic" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <a class="close" href="#" data-dismiss="modal">&times;</a>
            <div class="page-body">
                <div class="head">
                    <h3 style="margin-top:5px;">Registrazione avvenuta con successo</h3>

                </div>

                <h1 style="text-align:center;"><div class="checkmark-circle m-3">
                        <div class="background"></div>
                        <div class="checkmark draw"></div>

                    </div><h1>
                        <div class="text-center">
                        <a href="login.php" class="btn btn-outline-info">Accedi</a>
                        </div>
            </div>
        </div>
    </div>

</div>




    <div class="container">
        <div class="card shadow-lg o-hidden border-0 my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg">

                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="text-dark mb-4">Crea un Account!</h4>
                                <?php
                                //STAMPO EVENTUALI ERRORI
                                if($error!=null){
                                    echo "<p class='text-danger'>".$error."</p>";
                                }
                                ?>
                            </div>
                            <form class="user" method="post" action="register.php" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="text"  placeholder="Nome" name="nome" required></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="text"  placeholder="Cognome" name="cognome" required></div>
                                </div>
                                <div class="form-group"><input class="form-control form-control-user" type="text"   placeholder="Username" name="user" required></div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0"><input class="form-control form-control-user" type="password"  placeholder="Password" name="password" required></div>
                                    <div class="col-sm-6"><input class="form-control form-control-user" type="text"  placeholder="Stato" name="stato"></div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center"><img id="image" class="profile-pic rounded-circle mb-3 mt-4" src="assets/img/user.svg" width="200" height="200" />
                                        <div class="mb-3"><input type="file" id="inputfile" class="file-upload btn btn-primary btn-sm" accept="image/*" name="the_file"></div>
                                    </div>
                                </div>


                                <button class="btn btn-primary btn-block text-white btn-user" type="submit" name="submit">Registrati</button>
                                <hr>
                            </form>
                            <div class="text-center"><a class="small" href="login.php">Accedi</a></div>
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
    <script>
        $(document).ready(function() {
         // dichiariazione della funzione come
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
//aggiunta attributo Src contenete l'url dell immagine a gli elementi di classe profile-pic
                    reader.onload = function (e) {
                        $('.profile-pic').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
                else {
                    $('.profile-pic').attr('src', "assets/img/user.svg");
                }
            }

//Creazione evento cambiamento dei tag con classe file-upload dove viene passato alla funzione readURL viene passato l'intero oggetto
            $(".file-upload").on('change', function(){
                readURL(this);
            });

        });
    </script>
<?php
//MOSTRO MODAL DI AVVENUTA REGISTRAZIONE
if($ok){    echo "<script type='text/javascript'>
$(document).ready(function(){
$('#success_tic').modal('show');
});
</script>";
}


?>

</body>

</html>