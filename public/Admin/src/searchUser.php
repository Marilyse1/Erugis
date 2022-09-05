<?php
if(isset($_GET['valider'])) {
    $keywords = htmlspecialchars($_GET['keywords']);
    $valider = $_GET['valider'];
    if(isset($valider) && !empty($keywords)){
      $row = $db->prepare("SELECT * FROM user WHERE nom LIKE '%$keywords%' 
                                                      OR prenom LIKE '%$keywords%'
                                                      OR email LIKE '%$keywords%'
                                                      OR role LIKE '%$keywords%'
                                                      ORDER BY idUser DESC");
      $row->execute();
      $searchs = $row->fetchAll(PDO::FETCH_ASSOC);
      $nbrResult = $row->rowCount();
      if($nbrResult == 0){
        $message = "Aucun résultat pour la recherche";
      }
    }
}
      
?>