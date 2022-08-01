<?php
//CONNESSIONE AL DATABASE
$connessioneDB=mysqli_connect("localhost","root","","chat");
//VERIFICA DELLA AVVENUTA CONNESSIONE
if(!$connessioneDB){
    die("errore connessione al database");
}