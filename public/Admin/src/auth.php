<?php
function pagination(): array
{
    if(isset($_GET['page']) && !empty($_GET['page'])){
        $currentPage = (int) strip_tags($_GET['page']);
    }else{
        $currentPage = 1;
    }
    
    $sql = 'SELECT * FROM user u RIGHT JOIN presence p ON u.idUser = p.idUser GROUP BY u.idUser, date;';
    
    $statement = db()->prepare($sql);
    
    $statement->execute();
    
    $presences = $statement->rowCount();
    
    $parPage = 10;
    
    $pages = ceil($presences / $parPage);
    
    $premier = ($currentPage * $parPage) - $parPage;
    
    return [$currentPage, $pages, $parPage, $premier];
}

function presence_by_page(int $parPage, int $premier): array
{
    $sql = 'SELECT idPresence, u.idUser AS idUser, u.prenom AS prenom, u.nom AS nom, date
    FROM user u
    RIGHT JOIN presence p
    ON u.idUser = p.idUser
    GROUP BY idUser, date
    ORDER BY idPresence DESC
    LIMIT :premier, :parpage;';
    
    $statement = db()->prepare($sql);
    
    $statement->bindValue(':premier', $premier, PDO::PARAM_INT);
    $statement->bindValue(':parpage', $parPage, PDO::PARAM_INT);
    
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function all_presence(): array
{
    $sql = 'SELECT idPresence, u.idUser AS idUser, u.prenom AS prenom, u.nom AS nom, date
    FROM user u
    RIGHT JOIN presence p
    ON u.idUser = p.idUser
    GROUP BY idUser, date
    ORDER BY idPresence DESC;';
    
    $statement = db()->prepare($sql);

    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    
}


function number_all_presence(): int
{
    $sql = 'SELECT idPresence, u.idUser AS idUser, u.prenom AS prenom, u.nom AS nom, date
    FROM user u
    RIGHT JOIN presence p
    ON u.idUser = p.idUser
    GROUP BY idUser, date
    ORDER BY idPresence DESC;';
    
    $statement = db()->prepare($sql);
    
    $statement->execute();
    
    return $statement->rowCount();
}

function duration_work_by_day(array $presences): array
{
    $duree_de_travail = array();
    foreach($presences as $presence){
        $idUser = $presence['idUser'];
        $date = $presence['date'];
            $row = db()->prepare('SELECT * FROM user u RIGHT JOIN presence p ON u.idUser = p.idUser 
        WHERE u.idUser = :idUser AND date = :date
        ORDER BY idPresence ASC');   
        $row->execute(array('idUser'=>$idUser, 'date'=>$date ));
        $nbrResult1 = $row->rowCount();
        if(($nbrResult1 %2) == 0){

            $results = $row->fetchAll(PDO::FETCH_ASSOC);
            $heures = array();
            foreach($results as $result){
                $heures[] = $result['heure'];
            }
            $duree = 0;
            for($i=0; $i < $nbrResult1; $i=$i+2){
                $duree += duree($heures[$i],$heures[$i+1]);
            }
            $duree_to_heure = duree_to_heure($duree);
        
        }else if(($nbrResult1 %2) == 1){
        
            if(($nbrResult1-1) == 0){
                $duree_to_heure = "00:00";
            }else{
                $results = $row->fetchAll(PDO::FETCH_ASSOC);
                $heures = array();
                foreach($results as $result){
                    $heures[] = $result['heure'];
                }
                $duree = 0;
                $newheures = array_pop($heures);
                $nbrResult1 -= 1;
                for($i=0; $i < $nbrResult1; $i=$i+2){
                    $duree += duree($heures[$i],$heures[$i+1]);
                }
                $duree_to_heure = duree_to_heure($duree);
            }
        }
    
        $duree_de_travail[] = $duree_to_heure;
    }
    return $duree_de_travail;

}

function duree($heure1,$heure2){
	$minutes1=heure_to_minutes($heure1);
	$minutes2=heure_to_minutes($heure2);
	$duree = $minutes2-$minutes1;
	return $duree;
}

function heure_to_minutes($heure){
	$array_heure = explode(":",$heure);
	$minutes = 60*$array_heure[0] + $array_heure[1];
	return $minutes;
}

function duree_to_heure($duree){
	$m = $duree % 60;
	$h = ($duree - $m) / 60;
    switch ($h) {
        case 0: $h = "00"; break;
        case 1: $h = "01"; break;
        case 2: $h = "02"; break;
        case 3: $h = "03"; break;
        case 4: $h = "04"; break;
        case 5: $h = "05"; break;
        case 6: $h = "06"; break;
        case 7: $h = "07"; break;
        case 8: $h = "08"; break;
        case 9: $h = "09"; break;
    }
    switch ($m) {
        case 0: $m = "00"; break;
        case 1: $m = "01"; break;
        case 2: $m = "02"; break;
        case 3: $m = "03"; break;
        case 4: $m = "04"; break;
        case 5: $m = "05"; break;
        case 6: $m = "06"; break;
        case 7: $m = "07"; break;
        case 8: $m = "08"; break;
        case 9: $m = "09"; break;
    }
	$result = $h.":".$m;
	return $result;
}

function search()
{
    if(isset($_GET['recherche'])) {
        $keywords = htmlspecialchars($_GET['keywords']);
        $search = $_GET['recherche'];
        if(isset($search) && !empty($keywords)){
            $statement = db()->prepare("SELECT idPresence, u.idUser AS idUser, u.prenom AS prenom, u.nom AS nom, date FROM user u 
                                        RIGHT JOIN presence p
                                        ON u.idUser = p.idUser
                                        GROUP BY idUser, date
                                        HAVING date LIKE '%$keywords%'
                                      OR prenom LIKE '%$keywords%'
                                      OR nom LIKE '%$keywords%'
                                      ORDER BY idPresence DESC");
        
            $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
                
    }
    
    if(isset($_GET['periode'])){
        $originalFrom = $_GET['from'];
        $timestamp2 = strtotime($originalFrom);
        $from = date("d/m/y", $timestamp2);
        $originalTo = $_GET['to'];
        $timestamp1 = strtotime($originalTo);
        $to = date("d/m/y", $timestamp1);
        if(isset($to) && isset($from)){
            $statement = db()->prepare("SELECT idPresence, u.idUser AS idUser, u.prenom AS prenom, u.nom AS nom, date FROM user u 
                                            RIGHT JOIN presence p
                                            ON u.idUser = p.idUser
                                            GROUP BY u.idUser, date
                                            HAVING date  BETWEEN '$from' AND '$to'
                                            ORDER BY idPresence DESC");
        
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }

}

function search_number()
{
    if(isset($_GET['recherche'])) {
        $keywords = htmlspecialchars($_GET['keywords']);
            $search = $_GET['recherche'];
            if(isset($search) && !empty($keywords)){
                $statement = db()->prepare("SELECT idPresence, u.idUser AS idUser, u.prenom AS prenom, u.nom AS nom, date FROM user u 
                                            RIGHT JOIN presence p
                                            ON u.idUser = p.idUser
                                            GROUP BY idUser, date
                                            HAVING date LIKE '%$keywords%'
                                          OR prenom LIKE '%$keywords%'
                                          OR nom LIKE '%$keywords%'
                                          ORDER BY idPresence DESC");
                $statement->execute();
                if($statement->rowCount()>0){
                }else{
                    $message = "Aucun résultat pour la recherche";
                }
                return $statement->rowCount();
            }
        }
        
    }

    if(isset($_GET['periode'])){
        $originalFrom = $_GET['from'];
        $timestamp2 = strtotime($originalFrom);
        $from = date("d/m/y", $timestamp2);
        $originalTo = $_GET['to'];
        $timestamp1 = strtotime($originalTo);
        $to = date("d/m/y", $timestamp1);
        if(isset($to) && isset($from)){
            $statement = db()->prepare("SELECT idPresence, u.idUser AS idUser, u.prenom AS prenom, u.nom AS nom, date FROM user u 
                                            RIGHT JOIN presence p
                                            ON u.idUser = p.idUser
                                            GROUP BY u.idUser, date
                                            HAVING date  BETWEEN '$from' AND '$to'
                                            ORDER BY idPresence DESC");
            $statement->execute();
        if($statement->rowCount()>0){
        }else{
            $message = "Aucun résultat pour la recherche";
        }
        return $statement->rowCount();
    }
}

function update_user(): void
{
    if(isset($_POST['update'])) {
    $idUser = $_POST['update'];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $password = sha1($_POST['password']);
    $role = $_POST['role'];
    $sql = db()->prepare("UPDATE user SET nom = :nom, prenom = :prenom,  email = :email, password = :password, role = :role WHERE idUser = $idUser");   
    $sql->execute(array(
        'nom'=>$nom,
        'prenom'=>$prenom,
        'email'=>$email,
        'password'=>$password,
        'role'=>$role
    ));
    redirect_to('user.php');
}

}

function number_user()
{
    $sql = 'SELECT * FROM `user` ORDER BY `idUser` DESC;';
    $statement = db()->prepare($sql);

    $statement->execute();

    $statement->fetchAll(PDO::FETCH_ASSOC);

    return $statement->rowCount();
}

function user_list()
{
    $sql = 'SELECT * FROM `user` ORDER BY `idUser` DESC;';
    $statement = db()->prepare($sql);

    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function register_user(string $email, string $firstname, string $lastname, string $password, string $activation_code, string $role = "User", int $expiry = 1 * 24  * 60 * 60): bool
{
    $sql = 'INSERT INTO users(lastname, firstname, email, password, role, activation_code, activation_expiry)
            VALUES(:lastname, :firstname, :email, :password, :role, :activation_code,:activation_expiry)';

    $statement = db()->prepare($sql);
    $statement->bindValue(':lastname', $lastname,  PDO::PARAM_STR);
    $statement->bindValue(':firstname', $firstname,  PDO::PARAM_STR);
    $statement->bindValue(':email', $email,  PDO::PARAM_STR);
    $statement->bindValue(':password', sha1($password),  PDO::PARAM_STR);
    $statement->bindValue(':role', $role,  PDO::PARAM_STR);
    $statement->bindValue(':activation_code', password_hash($activation_code, PASSWORD_DEFAULT));
    $statement->bindValue(':activation_expiry', date('Y-m-d H:i:s',  time() + $expiry));

    return $statement->execute();
}
    
function find_user_presence_by_date()
{

}
function delete_user()
{
    if(isset($_POST['delete'])) {
        $idUser = $_POST['delete'];
        $sql = db()->prepare('DELETE FROM user WHERE idUser = $idUser');   
        $sql->execute();
    }
}

function send_activation_email(string $email, string $activation_code): void
{
    // create the activation link
    $activation_link = APP_URL . "/activate.php?email=$email&activation_code=$activation_code";

    // set email subject & body
    $subject = 'Please activate your account';
    $message = <<<MESSAGE
            Hi,
            Please click the following link to activate your account:
            $activation_link
            MESSAGE;
    // email header
    $header = "From:" . SENDER_EMAIL_ADDRESS;

    // send the email
    mail($email, $subject, nl2br($message), $header);

}

function generate_activation_code(): string
{
    return bin2hex(random_bytes(16));
}
?>