<?php
//AVVIO SESSIONE
session_start();
//IMPORTO FILE DI CONNESSIONE AL DATABASE
require "database.php";
//DICHIARO LA VARIABILE PER SALVARE EVENTUALI ERRORI
$error=false;
//VERIFICO LA PRESENZA DELLA SESSIONE
if(!isset($_SESSION["user"])){
//SE LA SESSIONE NON ESSISTE L'UTENTE VIENE RINDIRIZZATO ALLA PAGINA DI LOGIN
    header("Location: login.php");
}


$user = $_SESSION["user"];


//VERIFICO SE E' STATO SELEZIONATO UN UTENTE DESTINATARIO
if(isset($_GET["chat"])){
    $dest = $_GET["chat"];
    //VERIFCO SE IL GET SIA VUOTO
    if(empty($dest)){
        $chat=null;
        $dest=null;
    }
    //VERIFICO SE QUEL DETERMINATO UTENTE ESISTE TRAMITE LA QUERY
    else {
        $risultato = mysqli_query($connessioneDB, "SELECT * FROM utenti WHERE utenti.user='$dest'");

        if (mysqli_num_rows($risultato) == 0) {
            $chat = null;
            $dest = null;
            $error = true;

        } else {
            $dest = mysqli_fetch_assoc($risultato);
            $chat = $_GET["chat"];
        }

    }

    }

else{
    $chat=null;
    $dest=null;
}

//VERIFCO SE L'UTENTE HA RICHIESTO L'ELEMINAZIONE DI UNA EVENTUALE CHAT

if(isset($_GET["delete"])){
    //ESEQUO LA QUERY CHE ELIMINA LA CHAT TRA L'UTENTE LOGGATO E IL DESTINATARIO DEI MESSAGGI
    $sql = "DELETE FROM messaggi WHERE  messaggi.mittente='$user' and messaggi.destinatario='$chat' or messaggi.mittente='$chat' and messaggi.destinatario='$user'";
    mysqli_query($connessioneDB,$sql);
    header("Location: index.php");
}

if(isset($_POST["invia"])){
    //ESEGUO LA QUERY PER INVIARE IL MESSAGGIO
    if($chat!=null) {
        $testo = $_POST["testomessaggio"];
        $oggi = date("Y-m-d H:i:s");
        $sql = "INSERT INTO messaggi (mittente,destinatario,messaggio,data) VALUES ('$user','$chat','$testo','$oggi') ";
        mysqli_query($connessioneDB, $sql);
        header("Refresh:0");
    }
}

?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat and Discussion Platform</title>
    <link rel="stylesheet" href="assets/css/soho.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/themify-icons.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body class="white" id="body" >
<div id="errormodal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="icon-box">
                    <i class="material-icons">&#xE5CD;</i>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body text-center">
                <h4>Utente non trovato!</h4>
                <button class="btn btn-light" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- add friends modal -->
<div class="modal fade" id="addFriends" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti-user"></i> Nuova conversazione
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">Inizia un nuova conversazione.</div>
                <form method="get" action="index.php">
                    <div class="form-group">
                        <label for="user" class="col-form-label">Username</label>
                        <input type="text" class="form-control" id="emails" name="chat">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Invia</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ./ add friends modal -->




<!-- layout -->
<div class="layout">

    <!-- navigation -->
    <nav class="navigation">
        <div class="nav-group">
            <ul>
                <li>
                    <a class="logo" href="#" ">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="33.004px" height="33.003px" viewBox="0 0 33.004 33.003" style="enable-background:new 0 0 33.004 33.003;" xml:space="preserve">
                            <g>
                                <path d="M4.393,4.788c-5.857,5.857-5.858,15.354,0,21.213c4.875,4.875,12.271,5.688,17.994,2.447l10.617,4.161l-4.857-9.998
                                    c3.133-5.697,2.289-12.996-2.539-17.824C19.748-1.072,10.25-1.07,4.393,4.788z M25.317,22.149l0.261,0.512l1.092,2.142l0.006,0.01
                                    l1.717,3.536l-3.748-1.47l-0.037-0.015l-2.352-0.883l-0.582-0.219c-4.773,3.076-11.221,2.526-15.394-1.646
                                    C1.469,19.305,1.469,11.481,6.277,6.672c4.81-4.809,12.634-4.809,17.443,0.001C27.919,10.872,28.451,17.368,25.317,22.149z"></path>
                                <g>
                                    <circle cx="9.835" cy="16.043" r="1.833"></circle>
                                    <circle cx="15.502" cy="16.043" r="1.833"></circle>
                                    <circle cx="21.168" cy="16.043" r="1.833"></circle>
                                </g>
                            </g>
                            <g>
                        </svg>
                    </a>
                </li>
                <li>
                    <a data-navigation-target="chats" class="active" href="#">
                        <i class="ti-comment-alt"></i>
                    </a>
                </li>

                <li>
                    <a href="profile.php"  >
                        <i class="ti-settings"></i>
                    </a>
                </li>
                <li>
                    <a href="esci.php">
                        <i class="ti-power-off"></i>
                    </a>
                </li>
                <li>
                    <a>
                        <i class="ti-m"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- ./ navigation -->

    <!-- content -->
    <div class="content">

        <!-- sidebar group -->
        <div class="sidebar-group">

            <!-- Chats sidebar -->
            <div id="chats" class="sidebar active">
                <header>
                    <span>Chats</span>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a class="btn btn-light" href="#" data-toggle="modal" data-target="#addFriends">
                                <i class="ti-plus btn-icon"></i> Nuova conversazione
                            </a>
                        </li>
                        <li class="list-inline-item d-lg-none d-sm-block">
                            <a href="#" class="btn btn-light sidebar-close">
                                <i class="ti-close"></i>
                            </a>
                        </li>
                    </ul>
                </header>

                <div class="sidebar-body">
                    <ul class="list-group list-group-flush">
                        <?php
//ESEGUO LA QUERY CHE MI DA COME RISULTATO LE PERSONE CON QUI L'UTENTE HA AVUTO UNA CONVERSAZIONE
                        $risultato=mysqli_query($connessioneDB,"SELECT utenti.* FROM utenti,messaggi WHERE messaggi.mittente='$user' and messaggi.destinatario=utenti.user or messaggi.destinatario='$user' and messaggi.mittente=utenti.user GROUP BY utenti.user");

                        //CICLO PER FARE IL FETCH DI TUTTI I RECORD
                        while ($row = mysqli_fetch_assoc($risultato)){
                            $utentetab = $row["user"];
                            //ESEQUO LA QUERY CHE MI DA COME RISULTATO L'ULTIMO MESSAGGIO CHE L'UTENTE HA TRA LE PERSONE CHE HA AVUTO UNA CONVERSAZIONE
                            $fecthmess=mysqli_query($connessioneDB,"SELECT messaggi.messaggio FROM messaggi WHERE messaggi.mittente='$user' and messaggi.destinatario='$utentetab' or messaggi.mittente='$utentetab' and messaggi.destinatario='$user' ORDER BY messaggi.data DESC LIMIT 1 ");
                            $ultimomess=mysqli_fetch_assoc($fecthmess);
//VERIFICO SE QUEL DETERMINATO DESTINATARIO E' UGUALE A QUELLO SCELTO E MODIFICO L'HTML DI CONSEGUENZA
                             if($row["user"]==$chat){

                                $conttatto= <<<contatto

                        <li class="list-group-item open-chat">
                            <a href="index.php?chat="{$row["user"]}">
                            <div>
                                <figure class="avatar">
                                    <img src="{$row["immagine"]}" class="rounded-circle">
                                </figure>
                            </div>
                            <div class="users-list-body">
                                <h5>{$row["nome"]} {$row["cognome"]}</h5>
                                <p>{$ultimomess["messaggio"]}</p>
                                <div class="users-list-action action-toggle">
                                    <div class="dropdown">
                                        <a data-toggle="dropdown" href="#" aria-expanded="false">
                                            <i class="ti-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 190px, 0px);" x-out-of-boundaries="">
                                                                                    <a href="index.php?chat={$row["user"]}&delete=true" class="dropdown-item">Cancella chat</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </li>

contatto;

                            }
                            else{
                                $conttatto= <<<contatto

                        <li class="list-group-item">
                            <a href="index.php?chat={$row["user"]}">
                            <div>
                                <figure class="avatar">
                                    <img src="{$row["immagine"]}" class="rounded-circle">
                                </figure>
                            </div>
                            <div class="users-list-body">
                                <h5>{$row["nome"]} {$row["cognome"]}</h5>
                                <p>{$ultimomess["messaggio"]}</p>
                                <div class="users-list-action action-toggle">
                                    <div class="dropdown">
                                        <a data-toggle="dropdown" href="#" aria-expanded="false">
                                            <i class="ti-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 190px, 0px);" x-out-of-boundaries="">   
                                            <a href="index.php?chat={$row["user"]}&delete=true" class="dropdown-item">Cancella chat</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </li>

contatto;
                            }
echo $conttatto;
                        }


                        ?>

                    </ul>
                </div>
            </div>
        </div>

        <!-- chat -->
        <div class="chat">
            <?php
            //VERIFICO SE IN QUESTO MOMENTO SE C'E' SELEZIONATO UN DESTINATARIO E MODIFICO L'HTML CON LE INFORMAZIONI DEL DESTINATARIO
            if($chat!=null){
                $head=<<<head
<div class="chat-header">
                <div class="chat-header-user">
                    <figure class="avatar avatar-lg">
                        <img src="{$dest["immagine"]}" class="rounded-circle">
                    </figure>
                    <div>
                        <h5>{$dest["nome"]} {$dest["cognome"]}</h5>
                        
                    </div>
                </div>
                <div class="chat-header-action">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="#" class="btn btn-secondary" data-toggle="dropdown" aria-expanded="false">
                                <i class="ti-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(1890px, 68px, 0px);">
                                <a href="index.php?chat={$dest["user"]}&delete=true" class="dropdown-item">Cancella chat</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

head;
                echo $head;

            }
            ?>

            <div class="chat-body" tabindex="1" style="overflow: hidden; outline: none;">
                <div class="messages">


                <?php

                if($chat!=null) {

//QUERY PER ESTRARRE I MESSAGGI TRA L'UTENTE LOGGATO E IL DESTINATARIO SCELTO
                    $sql = "SELECT messaggi.* FROM messaggi WHERE  messaggi.mittente='$user' and messaggi.destinatario='$chat' or messaggi.mittente='$chat' and messaggi.destinatario='$user' ORDER BY data ASC ";
                    $query = mysqli_query($connessioneDB, $sql);

//CICLO PER IL FETCH DI TUTTI I RECORD DATI DALLA QUERY
                    while ($messaggio = mysqli_fetch_assoc($query)) {
                        //VERIFICO SE IL MASSAGGIO E' UN MESSAGGIO INVIATO O RICEVUTO E MODIFICO L'HTML DI CONSEGUENZA
                        if ($messaggio["mittente"] == $user) {
                            $html = <<<hmtl
                    
                    <div class="message-item outgoing-message">
                        <div class="message-content">
                            {$messaggio["messaggio"]}
                           
                        </div>
                        <div class="message-action">
                            {$messaggio["data"]} 
                        </div>
                    </div>
                    
                
                                
hmtl;
                        }
                        else{
                            $html = <<<hmtl
                    
                    <div class="message-item">
                        <div class="message-content">
                            {$messaggio["messaggio"]}
                            
                        </div>
                        <div class="message-action">
                            {$messaggio["data"]} 
                        </div>
                    </div>
                
            
hmtl;
                        }
                        //STAMPO IL MESSAGGIO NELLA PAGINA
                        echo $html;
                    }
                }

                ?>
                </div>
                </div>

            <?php
            //VERIFICO SE IN QUESTO MOMENTO SE C'E' SELEZIONATO UN DESTINATARIO
            if($chat!=null){

                $html=<<<html
            <div class="chat-footer">
                <form action="index.php?chat={$chat}" method="post">
                    <input type="text" class="form-control" placeholder="Scrivi un messaggio" aria-label="Scrivi un messaggio" aria-describedby="button-addon2" name="testomessaggio">
                    <div class="form-buttons">  
                     <button class="btn btn-primary btn-floating" type="submit" name="invia" >
                            <i class="fa fa-send"></i>
                        </button>
                    </div>
                </form>
            </div>
html;
                //STAMPO IL FORM DI INVIO DEL MESSAGGIO
                echo $html;
            }
            ?>
                </div>
            </div>
        </div>


<!-- ./ layout -->

<!-- JQuery -->
<script src="https://soho.laborasyon.com/dark/vendor/jquery-3.4.1.min.js"></script>

<!-- Popper.js -->
<script src="https://soho.laborasyon.com/dark/vendor/popper.min.js"></script>

<!-- Bootstrap -->
<script src="https://soho.laborasyon.com/dark/vendor/bootstrap/bootstrap.min.js"></script>


<script src="https://soho.laborasyon.com/dark/vendor/jquery.nicescroll.min.js"></script>


<script src="https://soho.laborasyon.com/dark/dist/js/soho.min.js"></script>


<script src="assets/js/examples.js"></script>


<?php
//SE L'UTENTE SELEZIONATO NON E' STATO TROVATO NEL DATABASE MOSTRO IL MODAL DI ERRORE
if($error==true) {
    echo "<script type='text/javascript'>
$(document).ready(function(){
$('#errormodal').modal('show');
});
</script>";
}


?>

<div id="ascrail2000" class="nicescroll-rails nicescroll-rails-vr" style="width: 4px; z-index: auto; cursor: default; position: absolute; top: 136px; left: 1244px; height: 682px; opacity: 0; display: none;"><div class="nicescroll-cursors" style="position: relative; top: 0px; float: right; width: 4px; height: 575px; background-color: rgba(66, 66, 66, 0.2); border: 0px; background-clip: padding-box; border-radius: 5px;"></div></div><div id="ascrail2000-hr" class="nicescroll-rails nicescroll-rails-hr" style="height: 4px; z-index: auto; top: 814px; left: 460px; position: absolute; cursor: default; display: none; width: 784px; opacity: 0;"><div class="nicescroll-cursors" style="position: absolute; top: 0px; height: 4px; width: 788px; background-color: rgba(66, 66, 66, 0.2); border: 0px; background-clip: padding-box; border-radius: 5px; left: 0px;"></div></div></body>
</html>




<



