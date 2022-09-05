<?php
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}

$sql = 'SELECT COUNT(*) AS users FROM `user`;';

$query = $db->prepare($sql);

$query->execute();

$result = $query->fetch();

$users = (int) $result['users'];

$parPage = 10;

$pages = ceil($users / $parPage);

$premier = ($currentPage * $parPage) - $parPage;

$sql = 'SELECT * FROM `user` ORDER BY `idUser` DESC LIMIT :premier, :parpage;';

$query = $db->prepare($sql);

$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':parpage', $parPage, PDO::PARAM_INT);

$query->execute();

$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>