<?php
if(isset($_POST['in'])) {
    $time = date("H:i");
    $date = date("d/m/y");
    $type = "Arrivé";
    $row = db()->prepare('SELECT * FROM presence WHERE idUser = :idUser AND date = :date');   
    $row->execute(array('idUser'=>$_SESSION['id_user'], 'date'=>$date )); 
    $nbrArriver = $row->rowCount();
    if(($nbrArriver % 2) == 0){
        $sql = db()->prepare('INSERT INTO presence (idUser, type, heure, date) VALUES (?, ?, ?, ?)');   
        $sql->execute(array($_SESSION['id_user'], $type, $time, $date));
    }else{
        $message = "Impossible, il y a une Arrivée sans Sortie";
    }
}
?>