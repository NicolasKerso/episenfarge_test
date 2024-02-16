<?php
session_start();
// Contenu PHP dynamique pour les utilisateurs connectés en haut du fichier
if(isset($_SESSION['id_user'])) {
    // Afficher un contenu personnalisé pour les utilisateurs connectés
    echo '<p>Bienvenue, utilisateur!</p>';
    // Autres contenus dynamiques PHP ici
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- Les polices depuis Google Fonts -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Candal">
    <!-- Les fichiers CSS locaux -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body id="" data-spy="scroll" data-target=".navbar" data-offset="60">
<section id="fond" class="fond">
    <div class="bg-color">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="col-md-12">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"><img src="img/logo.png" class="img-responsive" style="width: 140px; margin-top: -16px;"></a>
                    </div>
                    <div class="collapse navbar-collapse navbar-right" id="navbar1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#fond">Accueil</a></li>
                            <li class=""><a href="#service">Services</a></li>
                            <li class=""><a href="Authentification.php">Connexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="fond-info">
                    <div class="fond-logo text-center">
                        <img src="img/logo.png" class="img-responsive">
                    </div>
                    <div class="fond-text text-center">
                        <h1 class="white">Votre Santé avant tout!!!</h1>
                        <p>Épanouissez-vous pleinement avec notre programme dédié à votre bien-être intégral<br></p>
                        <a href="#contact" class="btn btn-appoint">Prise de rendez-vous</a>
                    </div>
                    <div class="overlay-detail text-center">
                        <a href="#service"><i class="fa fa-angle-down"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ fond-->

<!--service-->
<section id="service" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <h2 class="ser-title">Services</h2>
                <hr class="botm-line">
            </div>
            <div class="col-md-4 col-sm-4">

                <div class="service-info">
                    <div class="icon">
                        <i class="fa fa-medkit"></i>
                    </div>
                    <div class="icon-info">
                        <h4>Soins de santé haut de gamme</h4>
                    </div>
                </div>


                <div class="service-info">
                    <div class="icon">
                        <i class="fa fa-ambulance"></i>
                    </div>
                    <div class="icon-info">
                        <h4>Services d'urgence</h4>
                    </div>
                </div>
                <div class="service-info">
                    <div class="icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="icon-info">
                        <h4>Horaires d'ouverture</h4>
                        <p>Lundi - Vendredi: 08h - 17h<br>
                            Samedi: 09h - 18h</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="service-info">
                    <div class="icon">
                        <i class="fa fa-user-md"></i>
                    </div>
                    <div class="icon-info">
                        <h4>Conseil médical</h4>
                    </div>
                </div>

                <div class="service-info">
                    <div class="icon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div class="icon-info">
                        <h4>Prise de rendez-vous Rapidement</h4>
                    </div>
                </div>

                <div class="service-info">
                    <div class="icon">
                        <i class="fa fa-info-circle"></i>
                    </div>
                    <div class="icon-info">
                        <h4>Plus d'information sur le bracelet</h4>
                        <a href="images/Modedemploi.pdf" download="Document">
                            <button style="cursor:pointer;">Télécharger le document</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ service-->
<script src="js/jquery.min.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
<script src="contactform/contactform.js"></script>

</body>

</html>
