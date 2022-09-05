<?php

$inputs = [];
$errors = [];

if (is_post_request()) {

    [$inputs, $errors] = filter($_POST, [
        'email' => 'email| required',
        'password' => 'string| required',
        'remember_me' => 'string'
    ]);

    if ($errors) {
        redirect_with('login.php', ['errors' => $errors, 'inputs' => $inputs]);
    }

    // if login fails
    if (!login($inputs['email'], $inputs['password'], isset($inputs['remember_me']))) {

        $errors['login'] = 'Nom d’utilisateur ou mot de passe non valide';

        redirect_with('login.php', [
            'errors' => $errors,
            'inputs' => $inputs
        ]);
    }else {
        if(role(!$inputs['email'])){
            redirect_to('dashbord/index.php');
        }else{
            redirect_to('choice.php');
        }
    }
    
} else if (is_get_request()) {
    [$errors, $inputs] = session_flash('errors', 'inputs');
}
?>