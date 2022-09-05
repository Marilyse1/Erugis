<?php
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/register.php';
?>

<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="user.php" method="POST">
                    <div class="mb-3">
                        <label class="col-form-label">Nom</label>
                        <input type="text" class="form-control" name="lastname"
                            value="<?= $inputs['lastname'] ?? '' ?>">
                        <small><?= $errors['lastname'] ?? '' ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Prénoms</label>
                        <input type="text" class="form-control" name="firstname"
                            value="<?= $inputs['firstname'] ?? '' ?>">
                        <small><?= $errors['firstname'] ?? '' ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $inputs['email'] ?? '' ?>">
                        <small><?= $errors['email'] ?? '' ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password"
                            value="<?= $inputs['password'] ?? '' ?>">
                        <small><?= $errors['password'] ?? '' ?></small>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password2"
                            value="<?= $inputs['password2'] ?? '' ?>">
                        <small><?= $errors['password2'] ?? '' ?></small>
                    </div>
                    <div class="mb-3">
                        <select name="role" class="form-control">
                            <option selected="selected" value="User">User</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>