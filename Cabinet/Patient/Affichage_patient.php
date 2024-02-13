<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="card">
        <h5 class="card-header">Information Personnelle de patient</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered first">
                    <thead>
                    <tr>
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date Naissance</th>
                        <th>Téléphone</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    include "../connexion.php";
                    session_start();
                    $loggedInUser = $_SESSION['Username'];

                    // Vérifier si l'utilisateur a la fonctionnalité "Patient"
                    $queryUser = "SELECT * FROM `users` WHERE `UserName`='$loggedInUser' AND `FonctionL`='Patient'";
                    $resultUser = mysqli_query($con, $queryUser);

                    if (mysqli_num_rows($resultUser) > 0) {
                        // Récupérer l'ID du patient associé à l'utilisateur connecté
                        $rowUser = mysqli_fetch_assoc($resultUser);
                        $patientId = $rowUser['Id_pat'];

                        // Récupérer les informations du patient
                        $queryP = "SELECT `Id_pat`, `CIN`, `Nom_Pat`, `Prenom_Pat`, DATE_FORMAT(DateNaissance, '%d/%m/%Y') as dateN, `Telephone` FROM `patient` WHERE `Id_pat`='$patientId'";
                        $resultP = mysqli_query($con, $queryP);

                        while ($rowP = mysqli_fetch_assoc($resultP)) {
                            ?>
                            <tr>
                                <td><?php echo str_replace("`", "'", $rowP['CIN']); ?></td>
                                <td><?php echo str_replace("`", "'", $rowP['Nom_Pat']); ?></td>
                                <td><?php echo str_replace("`", "'", $rowP['Prenom_Pat']); ?></td>
                                <td><?php echo str_replace("`", "'", $rowP['dateN']); ?></td>
                                <td><?php echo str_replace("`", "'", $rowP['Telephone']); ?></td>
                                <td>
                                    <center>
                                        <a href="#" onclick="funtionGetUpdate(<?php echo $rowP['Id_pat']; ?>)">
                                            <i title="Modifier" class="far fa-edit fa-lg" style="color: green;"></i>
                                        </a>
                                        <a href="#" onclick="functionRemovePat(<?php echo $rowP['Id_pat']; ?>)">
                                            <i title="Supprimer" class="fa fa-trash fa-lg" style="color: red;" aria-hidden="true"></i>
                                        </a>
                                    </center>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function funtionGetUpdate(idPas) {
        $.ajax({
            type: "POST",
            url: "Patient/Update.php",
            data: { idPas: idPas },
            success: function(result) {
                $('.modal-body').html(result);
                $('#exampleModal').modal('show');
            }
        });
    }

    function functionRemovePat(idPas) {
        $.ajax({
            type: "POST",
            url: "Patient/SuppressionP.php",
            data: { idPas: idPas },
            success: function(result) {
                $('.modal-body').html(result);
                $('#exampleModal').modal('show');
            }
        });
    }
</script>
