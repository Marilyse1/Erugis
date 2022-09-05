<?php
require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/inc/header.php';
$result = pagination();
$currentPage = $result[0];
$pages = $result[1];
$parPage = $result[2];
$premier = $result[3];

$presences = presence_by_page($parPage, $premier);
$search = search();
$nbrResult = search_number() ?? number_all_presence();
$presences = $search ?? $presences;
$duree_de_travail = duration_work_by_day($presences);
$i = 0;
?>

<main class="main">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card background mb-3" style="max-width: 18rem;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            </svg>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h3><?= number_user() ?> User</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="container shadow-lg presence">
    <div class="row">
        <div class="col-12 col-lg-4">
            <form action="index.php" method="GET" class="form-inline">
                <div class="input-group">
                    <div class="input-group-prepend"><input type="date" name="from" class="form-control"></div>
                    <input type="date" name="to" class="form-control" required>
                    <div class="input-group-append">
                        <button type="submit" name="periode" class="btn btn-primary" value="search">Recherche</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8"></div>
        <div class="col-lg-1 col-2">
            <a href="printPdf.php" class="btn btn-success pull-right"></span> Export</a>
        </div>
        <div class="col-lg-3 col-10">
            <form method="GET" action="" class="d-flex" role="search">
                <input class="form-control me-2" id="myInput" name="keywords" type="search" placeholder="Search"
                    aria-label="Search" required="required">
                <button class="btn btn-outline-primary" value="search" name="recherche" type="submit">Search</button>
            </form>
        </div>
    </div>
    <div class="row" class="accordion" id="Exempleaccordion">
        <div class="col-12">
            <small>Présence réçente</small>
        </div>
        <div class="col-12" id="premierTitre">
            <table class="table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom</th>
                        <th>Prénoms</th>
                        <th>Date</th>
                        <th>Durée de travail</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                    foreach($presences as $presence){
                    ?>
                    <tr>
                        <td><?= $nbrResult ?></td>
                        <td><?= $presence['nom'] ?></td>
                        <td><?= $presence['prenom'] ?></td>
                        <td><?= $presence['date'] ?></td>
                        <td><?= $duree_de_travail[$i] ?></td>
                        <td>
                            <button class="btn btn-success" type="button" data-bs-toggle="collapse"
                                data-bs-target="#premierCollapse<?= $nbrResult ?>" aria-expanded="false"
                                aria-controls="premierCollapse<?= $nbrResult ?>">+</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="hiddenRow">
                            <div id="premierCollapse<?= $nbrResult ?>" class="collapse" aria-labelledby="premierTitre"
                                data-parent="#Exempleaccordion">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>N°</th>
                                            <th>Type</th>
                                            <th>Heure</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <?php
                                            $idUser = $presence['idUser'];
                                            $date = $presence['date'];
                                            $row = db()->prepare('SELECT * FROM user u RIGHT JOIN presence p ON u.idUser = p.idUser WHERE u.idUser = :idUser AND date = :date ORDER BY idPresence ASC');   
                                            $row->execute(array('idUser'=>$idUser, 'date'=>$date ));
                                            $results1 = $row->fetchAll(PDO::FETCH_ASSOC);
                                            $j = 1;
                                            foreach($results1 as $result1){
                                        ?>
                                        <tr>
                                            <td><?= $j ?></td>
                                            <td><?= $result1['type'] ?></td>
                                            <td><?= $result1['heure'] ?></td>
                                        </tr>
                                        <?php
                                            $j++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <?php
                        $i++;
                        $nbrResult--;
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

<?php require __DIR__ . '/../src/inc/footer.php'; ?>