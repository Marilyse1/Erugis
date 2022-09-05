<?php
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/inc/header.php';

$result1 = pagination();
$pages = $result1[1];
$currentPage = $result1[0];
$parPage = $result1[2];
$premier = $result1[3];
$presences = presence_list($parPage, $premier);
                    
//ALTER TABLE arriver ADD CONSTRAINT idUser FOREIGN KEY (idUser) REFERENCES user (idUser);
?>
<?php //require_once('header.php'); ?>

    <div class="container">
        <div class="row">
            <div class="col-12 titre">
                <h1 class="text-center">Soyez la <strong>BIENVENUE</strong> sur le site présence.</h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row shadow-lg reste">
            <div class="col-12 presence">
                <?php if(isset($message)){ ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert"><?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" arie-label="Close"></button>
                </div>
                <?php } ?>
                <form method="POST" action="">
                    <?php
                    if(empty($_SESSION)){
                    ?>
                    <h1 class="text-center">Marquez votre arrivé ici.👉
                        <button class="button">Je suis là</button>
                    </h1>
                    <h1 class="text-center">Marquez votre sortie ici.👉
                        <button class="button">Je rentre</button>
                    </h1>
                    <?php 
                    }else{
                    ?>
                    <h1 class="text-center">Marquez votre arrivé ici.👉
                        <button name="in" class="button">Je suis là</button>
                    </h1>
                    <h1 class="text-center">Marquez votre sortie ici.👉
                        <button name="out" class="button">Je rentre</button>
                    </h1>
                    <?php 
                    }
                    ?>
                </form>
            </div>
            <div class="col-12">
                <h2 class="text-center titreTableau">Les personnes présente aujourd'hui le <?= date('d/m/y'); ?>
                </h2>
            </div>
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom</th>
                            <th>Prénoms</th>
                            <th>Type</th>
                            <th>Heure</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                            $i = 1;
                            foreach($presences as $presence){
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $presence['lastname'] ?></td>
                            <td><?= $presence['firstname'] ?></td>
                            <td><?= $presence['type'] ?></td>
                            <td><?= $presence['heure'] ?></td>
                        </tr>
                        <?php
                            $i++; 
                            }
                        ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-center">
                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a href="./index.php?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                        </li>
                        <?php for($page = 1; $page <= $pages; $page++): ?>
                        <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                        <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                            <a href="./index.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                        </li>
                        <?php endfor ?>
                        <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                            <a href="./index.php?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <?php 
    require __DIR__ . '/../src/inc/footer.php';?>
    