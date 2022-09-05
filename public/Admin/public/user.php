<?php

require __DIR__ . '/../src/bootstrap.php';
//require __DIR__ . '/../src/register.php';

require_once __DIR__ . '/../src/inc/headerUser.php';
require 'add_user.php';

?>

<div class="container">
    <div class="row">
        <div class="col titre">
            <h1 class="text-center"><em>Liste des utilisateurs</em></h1>
        </div>
    </div>
</div>

<div class="container">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">
                        Ajouter
                    </button>
                </div>
                <div class="col-9"></div>
                <div class="col-1">
                    <a href="printPdfUser.php" class="btn btn-success pull-right"><span
                            class="glyphicon glyphicon-print"></span> PDF</a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="example">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Prénoms</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        $users = user_list();
                        $users = $searchs ?? $users;
                        $i = 1;
                        foreach($users as $user){
                        ?>
                        <tr>
                            <th><?= $i ?></th>
                            <td><?= $user['nom'] ?></td>
                            <td><?= $user['prenom'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['role'] ?></td>
                            <td>
                                <a href="update_user.php?id=<?= $user['idUser'] ?>" class="m-2"><i
                                        class="fa fa-edit fa-2x"></i></a>
                                <a href="delete.php?id=<?= $user['idUser'] ?>" class="m-2"><i class="fa fa-trash fa-2x"
                                        style="color:red;"></i></a>
                            </td>
                        </tr>
                        <?php 
                        $i++;
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../src/inc/footer.php'; ?>