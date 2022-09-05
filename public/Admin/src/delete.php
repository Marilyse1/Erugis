<?php
require __DIR__ . '/../src/bootstrap.php';
    $idUser = $_GET['id'];
    echo $idUser;
    $sql = db()->prepare("DELETE FROM user WHERE idUser = $idUser");   
    $sql->execute();
    redirect_to('user.php');

?>