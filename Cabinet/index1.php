<?php session_start();
if(isset($_SESSION['id_user']) && $_SESSION['Fonction'] === 'Medecin'){;?>

    <!doctype html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/libs/css/style.css">
        <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
        <link rel="stylesheet" href="assets/vendor/charts/chartist-bundle/chartist.css">
        <link rel="stylesheet" href="assets/vendor/charts/morris-bundle/morris.css">
        <link rel="stylesheet" href="assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="assets/vendor/charts/c3charts/c3.css">
        <link rel="stylesheet" href="assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
        <link href="multiSelect/css/styleMu.css" rel="stylesheet">
          <link rel='stylesheet' type='text/css' href='assets/css/reset.css' />
          <link rel='stylesheet' type='text/css' href='assets/css/jquery.weekcalendar.css' />
          <link rel='stylesheet' type='text/css' href='assets/css/eventcalendar.css' />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.3.1/socket.io.js"></script>

        <title>Gestion des dossiers médicaux des patients</title>
    </head>
    <body onload="functionGetImageBody();">
    <div class="dashboard-main-wrapper">
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="#">Dr <?php echo $_SESSION['Username'];?>
                    : <?php echo $_SESSION['Specialite'];?> </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item">
                            <div id="custom-search" class="top-search-bar">
                            </div>
                        </li>
                        <li class="nav-item dropdown notification">
                            <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-fw fa-bell"></i> <span class="indicator"></span></a>
                        </li>
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/avatar-1.jpg" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['Username'];?> </h5>
                                </div>
                                <a class="dropdown-item" href="#" onclick="changePass();"><i class="fas fa-cog mr-2"></i>Changement mot de passe </a>
                                <a class="dropdown-item" href="logOut.php"><i class="fas fa-power-off mr-2"></i>Sortir</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">
                                <b style="color: white"> Nom Cabinet
                                    <a title="Username-less login" class="modalC"  href="AjouterPatient.php" >
                                    </a>
                                </b>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link active"  href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1" aria-controls="submenu-1"><i class="fa fa-fw fa-user-circle"></i>Dossiers médicaux des patients </a>
                                <div id="submenu-1" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" onclick="getPatient();" href="#">Patient</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" onclick="getHistoPat();" href="#">Historique </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse"
                                   onclick="functionGetAllConsultation();"
                                   aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-rocket"></i> Consultations &
                                    Contrôle </a>
                            </li>
                            <li class="nav-divider">
                                <!-- Features-->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="getordonnance();" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6"><i class="fas fa-fw fa-file"></i> Ordonnances</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link active"  href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-9" aria-controls="submenu-9"><i class="fa fa-fw fa-user-circle"></i>Rendez-vous </a>
                                <div id="submenu-9" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" onclick="getRDV();" href="#">Liste des rendez-vous</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            </li>

                            <li class="nav-item ">
                                <a class="nav-link"  href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-8" aria-controls="submenu-8"><i class="fas fa-ambulance"></i>analyse & radiologique</a>
                                <div id="submenu-8" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" onclick="patientAnalyse();" href="#">analyse & radiologique Patient </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" onclick="parametrageanalyse();"  > Paramétrage d'analyse & radiologique</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" onclick="GetMedicament();" href="#"
                                   data-toggle="collapse" aria-expanded="false" data-target="#submenu-10" aria-controls="submenu-10">
                                    <i class="fas fa-notes-medical"></i> Médicaments</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="Draccess();" data-toggle="collapse" aria-expanded="false" data-target="#submenu-10" aria-controls="submenu-10"><i class="fas fa-f fa-folder" id="Convocation"></i> droits d’accès </a>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <div id="AfficheAllhere"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ChanegPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ChanegPass">Chanegement de mot de passe </h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-1.12.4.js"></script>
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="assets/libs/js/main-js.js"></script>
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- multiSelect -->
    <script src="multiSelect/js/bundle.min.js"></script>
    <!-- multiSelect -->
    <script src="assets/js/jquery-ui.js"></script>
    <script  src="assets/js/datepickerFr.js"></script>
    <script src="canvas/canvasjs.js"></script>
    </body>
    <script type="text/javascript">



        function Draccess()
        {
            $("#AfficheAllhere").load("Access/Access.php");
        }
        function getPatient()
        {
            $("#AfficheAllhere").load("Patient/InformationPe.php");
        }
        function getRDV()
        {
            $("#AfficheAllhere").load("rendez_vous/Affichage_rdv.php");
        }
        function GetMedicament()
        {
            $("#AfficheAllhere").load("Medicament/Medicament.php");
        }
        function functionGetAllConsultation()
        {
            $("#AfficheAllhere").load("consultation/consultation.php");
        }
        function  getHistoPat()
        {
            $("#AfficheAllhere").load("histoPat/histoPat.php");
        }
        function  getordonnance()
        {
            $("#AfficheAllhere").load("ordonnance/ordonnance.php");
        }

        function Draccess()
        {
            $("#AfficheAllhere").load("Access/Access.php");
        }


        function parametrageanalyse()
        {
            $("#AfficheAllhere").load("panalyseRad/ParamAnalyse.php");
        }
        function patientAnalyse()
        {
            $("#AfficheAllhere").load("analyse/analyse.php");
        }
        function functionGetImageBody()
        {
            $("#AfficheAllhere").load("body.php");
        }


        function changePass()
        {
            $.ajax({
                type: "POST",
                url: "Access/changePass.php",
                success: function(result) {
                    $('.modal-body').html(result);
                    $('#ChanegPass').modal('show');
                }
            });
        }
    </script>
    </html>

<?php }
else {
    header('Location: /Cabinet/Authentification.php');
}
?>
