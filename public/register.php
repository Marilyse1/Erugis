<?php

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/register.php';
?>

<?php view('header', ['title' => 'Inscription']) ?>

<div class="container">
    <div class="row">
        <div class="col titre">
            <h1 class="text-center"><em>Inscription</em></h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row shadow-lg reste justify-content-md-center">
        <div class="col">
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="lastname" value="<?= $inputs['lastname'] ?? '' ?>"
                        class="form-control <?= error_class($errors, 'lastname') ?>" placeholder="Entrez votre nom"
                        autocomplete="off">
                    <small><?= $errors['lastname'] ?? '' ?></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="firstname" value="<?= $inputs['firstname'] ?? '' ?>" class="form-control"
                        placeholder="Entrez votre prénom" class="<?= error_class($errors, 'firstname') ?>">
                    <small><?= $errors['firstname'] ?? '' ?></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?= $inputs['email'] ?? '' ?>" class="form-control"
                        placeholder="Entrez votre email" class="<?= error_class($errors, 'email') ?>">
                    <small><?= $errors['email'] ?? '' ?></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" value="<?= $inputs['password'] ?? '' ?>" class="form-control"
                        class="<?= error_class($errors, 'password') ?>"
                    placeholder="Entrez votre mot de passe">
                    <small><?= $errors['password'] ?? '' ?></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer mot de passe</label>
                    <input type="password" name="password2" value="<?= $inputs['password2'] ?? '' ?>"
                        class="form-control" class="<?= error_class($errors, 'password2') ?>"
                        placeholder="Confirmer votre mot de passe">
                    <small><?= $errors['password2'] ?? '' ?></small>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button><br>
                <div class="text-end">Vous avez déja un compte? <a href="login.php">Connectez-vous</a></div>
            </form>
        </div>
    </div>
</div>
<?php view('footer') ?>