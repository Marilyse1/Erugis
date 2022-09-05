<?php
function pagination(): array
{
    if(isset($_GET['page']) && !empty($_GET['page'])){
        $currentPage = (int) strip_tags($_GET['page']);
    }else{
        $currentPage = 1;
    }
    
    $date = date("d/m/y");
    
    $sql = 'SELECT COUNT(*) AS presences FROM users u RIGHT JOIN presence p ON u.id_user = p.idUser WHERE date = :date';
    
    $statement = db()->prepare($sql);
    $statement->bindValue(':date', $date, PDO::PARAM_INT);
    $statement->execute();
    
    $result = $statement->fetch();
    
    $presences = (int) $result['presences'];
    
    $parPage = 10;
    
    $pages = ceil($presences / $parPage);
    
    $premier = ($currentPage * $parPage) - $parPage;

    return [$currentPage, $pages, $parPage, $premier];

}

function presence_list(int $parPage, int $premier): array
{
    $date = date("d/m/y");
    $sql = 'SELECT * FROM users u RIGHT JOIN presence p ON u.id_user = p.idUser WHERE date = :date ORDER BY idPresence ASC LIMIT :premier, :parpage ;';
    
    $statement = db()->prepare($sql);
    
    $statement->bindValue(':premier', $premier, PDO::PARAM_INT);
    $statement->bindValue(':parpage', $parPage, PDO::PARAM_INT);
    $statement->bindValue(':date', $date, PDO::PARAM_INT);
    
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function is_user_logged_in(): bool
{
    // check the session
    if (isset($_SESSION['email'])) {
        return true;
    }

    // check the remember_me in cookie
    $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);

    if ($token && token_is_valid($token)) {

        $user = find_user_by_token($token);

        if ($user) {
            return log_user_in($user);
        }
    }
    return false;
}

function logout(): void
{
    if (is_user_logged_in()) {

        // delete the user token
        delete_user_token($_SESSION['id_user']);

        // delete session
        unset($_SESSION['email'], $_SESSION['id_user`']);

        // remove the remember_me cookie
        if (isset($_COOKIE['remember_me'])) {
            unset($_COOKIE['remember_me']);
            setcookie('remember_user', null, -1);
        }

        // remove all session data
        session_destroy();

        // redirect to the login page
        redirect_to('index.php');
    }
}



function log_user_in(array $user): bool
{
    // prevent session fixation attack
    if (session_regenerate_id()) {
        // set username & id in the session
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['id_user']  = $user['id_user'];
        return true;
    }

    return false;
}


?>