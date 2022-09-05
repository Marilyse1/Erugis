
<!DOCTYPE html>
<html>

<head>
    <link href="image/logo.png" rel="icon">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Erugis</title>
</head>

<body>
    <div class="shadow-sm">
        <div class="container">
            <div class="row">
                <nav class="col navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">
                            <img src="image/logo.png" alt="Notre logo" height="50"
                                class="d-inline-block align-text-top">
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Accueil</a>
                                </li>
                            </ul>

                            <?php
                            if(isset($_SESSION['firstname']) && isset($_SESSION['lastname'])){
                                ?>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" style="border:1px solid black; border-radius: 25px;" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false"><span
                                                    style="background-color:#262b59;color:white;border-radius:25px;margin-right: 0px;margin: 5px; padding: 5px; ">
                                        <?php echo ucfirst(strtolower($_SESSION['firstname'][0])); 
                                        echo ucfirst(strtolower($_SESSION['lastname'][0]));
                                        ?></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="">
                                                <button
                                                    style="background-color:#262b59;color:white;border-radius:25px;">AA</button>
                                                <?= $_SESSION['firstname']." ".$_SESSION['lastname']
                                        ?>
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#">Mon Tableau de bord</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><A class="dropdown-item" href="logout.php">Déconnexion</A>
                                        </li>
                                    </ul>
                                </div>
                                
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>