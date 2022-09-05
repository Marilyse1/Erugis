<?php
$errors = [];
$inputs = [];

if (is_post_request()) {

    $fields = [
        'firstname' => 'string| required',
        'lastname' => 'string| required',
        'email' => 'email| required | email | unique: users, email',
        'password' => 'string| required | secure',
        'password2' => 'string| required |same: password'
    ];

    $messages = [
        'password2' => [
            'required' => 'Veuillez saisir à nouveau le mot de passe',
            'same' => 'Le mot de passe ne correspond pas'
        ]
    ];
    
    [$inputs, $errors] = filter($_POST, $fields, $messages);
    
    if ($errors) {
        redirect_with('user.php', [
            'inputs' => htmlspecialchars($inputs),
            'errors' => $errors
        ]);
    }

    $activation_code = generate_activation_code();
    //if(smtpMailer($inputs['email'], $activation_code)){
    //if (register_user($inputs['email'], $inputs['firstname'], $inputs['lastname'], $inputs['password'], $activation_code)) {

        // send the activation email
        //send_activation_email($inputs['email'], $activation_code);
        

        

       $result = smtpMailer($inputs['email'], $activation_code);
       if (true !== $result)
{
	// erreur -- traiter l'erreur
	echo $result;

}else{
    register_user($inputs['email'], $inputs['firstname'], $inputs['lastname'], $inputs['password'], $activation_code);
        redirect_with_message(
            'user.php',
            'Veuillez vérifier votre adresse e-mail pour activer votre compte avant de vous connecter'
        );}

    

} else if (is_get_request()) {
    [$inputs, $errors] = session_flash('inputs', 'errors');
}


