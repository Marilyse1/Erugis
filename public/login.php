<?php
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/login.php';
?>

<?php view('header', ['title' => 'Connexion']) ?>

<div class="container">
    <div class="row">
        <div class="col titre">
            <h1 class="text-center"><em>Connexion</em></h1>
        </div>
    </div>
</div>

<?php if (isset($errors['login'])) : ?>
<div class="alert alert-error">
    <?= $errors['login'] ?>
</div>
<?php endif ?>

<div class="container">
    <div class="row shadow-lg reste">
        <div class="col">
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Entrez votre email"
                        value="<?= $inputs['email'] ?? '' ?>">
                    <small><?= $errors['email'] ?? '' ?></small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe"
                        value="<?= $inputs['password'] ?? '' ?>">
                    <small><?= $errors['password'] ?? '' ?></small>
                </div>
                <div class="mb-3">
                    <input type="checkbox" name="remember_me" class="form-check-input"
                        value="checked <?= $inputs['remember_me'] ?? '' ?>">
                    <label class="form-check-label">Se souvenir de moi</label>
                    <small><?= $errors['agree'] ?? '' ?></small>
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button><br>
                <div class="text-end">Vous n'avez pas de compte? <a href="register.php">Inscrivez-vous</a></div>
            </form>
        </div>
    </div>
</div>

<?php view('footer') ?>