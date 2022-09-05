<?php
if(isset($_POST['out'])) {
    $time = date("H:i");
    $date = date("d/m/y");
    $type = "Sortie";
    $row = db()->prepare('SELECT * FROM presence WHERE idUser = :idUser AND date = :date');   
    $row->execute(array('idUser'=>$_SESSION['id_user'], 'date'=>$date )); 
    $nbrResult = $row->rowCount();
    if(($nbrResult % 2) == 1){
        $sql = db()->prepare('INSERT INTO presence (idUser, type, heure, date) VALUES (?, ?, ?, ?)');   
        $sql->execute(array($_SESSION['id_user'], $type, $time, $date));
    }else{
        $message = "Impossible car vous n'avez pas marqué d'Arrivée";
    }
}
?>