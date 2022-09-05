<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'mailer/vendor/autoload.php';

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


function find_user_by_email(string $email)
{
    $sql = 'SELECT *
            FROM users
            WHERE email=:email';

    $statement = db()->prepare($sql);
    $statement->bindValue(':email', $email);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function login(string $email, string $password, bool $remember = false): bool
{
    $user = find_user_by_email($email);

    if ($user && is_user_active($user) && strcmp(sha1($password), $user['password']) == 0) {

        log_user_in($user);

        if ($remember) {
            remember_me($user['id_user']);
        }

        return true;
    }

    return false;
}
function choice_connection(): void
{
    if(isset($_POST['user'])) {
        redirect_to('dashboard/index.php');
    }
    if(isset($_POST['admin'])) {
        redirect_to('Admin/index.php');
    }
    
}
function role(string $email): bool
{
    $user = find_user_by_email($email);
    if($user['role'] == "Admin"){
        return true;
    }
    else{
        return false;
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

function require_login(): void
{
    if (!is_user_logged_in()) {
        redirect_to('login.php');
    }
}

function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['email'];
    }
    return null;
}

function is_user_active($user)
{
    return (int)$user['active'] === 1;
}

function generate_activation_code(): string
{
    return bin2hex(random_bytes(16));
}

/*function send_activation_email(string $email, string $activation_code): void
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

}*/

function smtpMailer($to, $activation_code) {
    $activation_link = APP_URL . "/activate.php?email=$to&activation_code=$activation_code";
    $subject = 'Veuillez activer votre compte';
    $body = <<<MESSAGE
    Salut,
    Veuillez cliquer sur le lien suivant pour activer votre compte:
    $activation_link
    MESSAGE;
    $from_name = 'Erugis';
    $from = GMailUser;
	$mail = new PHPMailer();  
	$mail->IsSMTP(); 
	$mail->SMTPDebug = 1;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
	$mail->SMTPAuth = true;  
	$mail->Host = 'smtp.mailtrap.io';
	$mail->Port = 2525;
	$mail->Username = 'c10bc55af40c10';
	$mail->Password = '5fee94ed1d8d54';
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		return 'Erreur de messagerie: '.$mail->ErrorInfo;
	} else {
		return true;
	}
}

function find_unverified_user(string $activation_code, string $email)
{

    $sql = 'SELECT id_user, activation_code, activation_expiry < now() as expired
            FROM users
            WHERE active = 0 AND email=:email';

    $statement = db()->prepare($sql);

    $statement->bindValue(':email', $email);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // already expired, delete the in active user with expired activation code
        if ((int)$user['expired'] === 1) {
            delete_user_by_id($user['id_user']);
            return null;
        }
        // verify the password
        if (password_verify($activation_code, $user['activation_code'])) {
            return $user;
        }
    }

    return null;
}

function activate_user(int $id_user): bool
{
    $sql = 'UPDATE users
            SET active = 1,
                activated_at = CURRENT_TIMESTAMP
            WHERE id_user = :id_user';

    $statement = db()->prepare($sql);
    $statement->bindValue(':id_user', $id_user, PDO::PARAM_INT);

    return $statement->execute();
}
?>