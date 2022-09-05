<?php
require __DIR__ . '/../src/bootstrap.php';
update_user();
require __DIR__ . '/../src/inc/headerUser.php';
?>

<div class="container">
    <div class="row">
        <div class="col titre">
            <h1 class="text-center">Modifier les informations de l'utilisateur</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row shadow-lg presence">
        <div class="col">
            <?php
            if(isset($_GET['id'])){
                $idUser = $_GET['id'];
                $query = db()->prepare("SELECT * FROM user WHERE idUser = $idUser");
                $query->execute();
                if($query->rowCount() >0){
                    $user = $query->fetch(PDO::FETCH_ASSOC);
            ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="col-form-label">Nom</label>
                    <input type="text" class="form-control" name="nom" value="<?= $user['nom'] ?>">
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Prénoms</label>
                    <input type="text" class="form-control" name="prenom" value="<?= $user['prenom'] ?>">
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>">
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="password" value="<?= $user['password'] ?>">
                </div>
                <div class="mb-3">
                    <select name="role" class="form-control">
                        <option selected="selected" value="<?= $user['role'] ?>"><?= $user['role'] ?></option>
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="text-end">
                    <a href="user.php" class="btn btn-secondary">Close</a>
                    <button type="submit" name="update" value="<?= $user['idUser'] ?>"
                        class="btn btn-primary">Edit</button>
                </div>
            </form>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
</script>



</body>

</html>