<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="card">
        <h5 class="card-header">Liste des rendez-vous</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered first">
                    <thead>
                    <tr>
                        <th>Date de rendez-vous</th>
                        <th>Heure de début</th>
                        <th>Heure de fin</th>
                        <th>Titre</th>
                        <th>Observation</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    include "../connexion.php";

                    // Retrieve all appointments details
                    $queryAppointment = "SELECT `Id_Rend`, `DateRend`, `StartH`, `EndH`, `TitreRe`, `ObservationRend` FROM `rendezvous`";
                    $resultAppointment = mysqli_query($con, $queryAppointment);

                    while ($rowA = mysqli_fetch_assoc($resultAppointment)) {
                        ?>
                        <tr>
                            <td><?php echo str_replace("`", "'", $rowA['DateRend']); ?></td>
                            <td><?php echo str_replace("`", "'", $rowA['StartH']); ?></td>
                            <td><?php echo str_replace("`", "'", $rowA['EndH']); ?></td>
                            <td><?php echo str_replace("`", "'", $rowA['TitreRe']); ?></td>
                            <td><?php echo str_replace("`", "'", $rowA['ObservationRend']); ?></td>
                            <td>
                                <center>
                                    <a href="http://localhost:3000?id=<?php echo $rowA['Id_Rend']; ?>">

                                    <i title="Commencer un chat vidéo" class="fas fa-video fa-lg" style="color: blue;"></i>
                                    </a>
                                </center>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
