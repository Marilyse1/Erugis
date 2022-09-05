<?php require __DIR__ . '/../src/bootstrap.php'; ?>

<?php view('header', ['title' => 'Erugis']) ?>

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
            <h1 class="text-center">Marquez votre arrivé ici.👉
                <a href="login.php"><button class="button">Je suis là</button></a>
            </h1>

            <h1 class="text-center">Marquez votre sortie ici.👉
                <a href="login.php"><button class="button">Je rentre</button></a>
            </h1>
        </div>
        <div class="col-12">
            <h2 class="text-center titreTableau">Les personnes présente aujourd'hui le <?php echo date('d/m/y'); ?>
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
                    <tr></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php view('footer') ?>