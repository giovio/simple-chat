<?php


//DISTRUGGO LA SESSIONE E RIPORTO L'UTENTE NELLA PAGINA DI LOGIN
session_start();
session_destroy();
session_abort();
header("Location: login.php");