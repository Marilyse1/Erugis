<?php
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/choice.php';
?>
<?php view('header', ['title' => 'Choix de connexion']) ?>

<div class="container">
    <div class="row">
        <div class="col titre">
            <h1 class="text-center"><em>Choix de connexion</em></h1>
        </div>
    </div>
</div>

<div class="container">
    <div class="row shadow-lg reste">
        <div class="col">
            <form action="" method="POST" align="center">
                <button type="submit" name="user" class="btn btn-primary">User</button><br><br><br><br>
                <button type="submit" name="admin" class="btn btn-primary">Admin</button><br>
            </form>
        </div>
    </div>
</div>

<?php view('footer') ?>