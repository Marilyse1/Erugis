<?php
session_start();
//if(isset($_POST['deconnexion'])){
    $_SESSION = array();
    session_destroy();
    $d = $_SERVER['HTTP_REFERER'];
    $c = substr($d, 0, 23);
    header('Location: '.$c); 
//}
?>