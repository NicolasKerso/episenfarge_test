<?php
// Paramètres de connexion à la base de données
$serveur = "193.203.168.3"; // Adresse IP du serveur MySQL distant
$utilisateur = "u677866956_compte_test"; // Remplacez par votre nom d'utilisateur MySQL
$motdepasse = ";-k33vLYw:H9"; // Remplacez par votre mot de passe MySQL
$nom_bd = "u677866956_test"; // Nom de votre base de données en ligne

// Connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $nom_bd);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Graphique SPO2 et FC</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <form method='GET'>
        <select name='id_patient'>
            <?php
            // Requête pour récupérer les id_patient
            $query_id_patient = "SELECT DISTINCT id_patient FROM mesures";
            $result_id_patient = $connexion->query($query_id_patient);

            if ($result_id_patient->num_rows > 0) {
                while ($row_id_patient = $result_id_patient->fetch_assoc()) {
                    echo "<option value='" . $row_id_patient['id_patient'] . "'>" . $row_id_patient['id_patient'] . "</option>";
                }
            }
            ?>
        </select>
        <input type='submit' value='Afficher les informations'>
    </form>

    <?php
    // Affichage des résultats en fonction de l'id_patient sélectionné
    if (isset($_GET['id_patient'])) {
        $id_patient_selected = $_GET['id_patient'];
        $query_mesures = "SELECT * FROM mesures WHERE id_patient = '$id_patient_selected'";
        $result_mesures = $connexion->query($query_mesures);

        // Affichage des résultats
        echo "<h2>Résultats pour l'id_patient : $id_patient_selected</h2>";
        if ($result_mesures->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Id capture</th><th>Id patient</th><th>Id capteur</th><th>FC</th><th>SPO2</th><th>Alerte</th><th>Timestamp</th></tr>";
            while ($row_mesures = $result_mesures->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_mesures["id_capture"] . "</td>";
                echo "<td>" . $row_mesures["id_patient"] . "</td>";
                echo "<td>" . $row_mesures["id_capteur"] . "</td>";
                echo "<td>" . $row_mesures["fc"] . "</td>";
                echo "<td>" . $row_mesures["spo2"] . "</td>";
                echo "<td>" . $row_mesures["alerte"] . "</td>";
                echo "<td>" . $row_mesures["timestamp"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Préparation des données pour le graphique
            $timestamps = array();
            $spo2_values = array();
            $fc_values = array();

            $result_mesures->data_seek(0); // Réinitialiser le pointeur de résultat

            while ($row_mesures = $result_mesures->fetch_assoc()) {
                $timestamps[] = $row_mesures["timestamp"];
                $spo2_values[] = $row_mesures["spo2"];
                $fc_values[] = $row_mesures["fc"];
            }

            // Génération du script JavaScript pour le graphique
            echo "<h2>Graphique SPO2 et FC</h2>";
            echo "<canvas id='spo2FcChart' width='800' height='400'></canvas>";
            ?>
            <script>
                var ctx = document.getElementById('spo2FcChart').getContext('2d');
                var spo2FcChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($timestamps); ?>,
                        datasets: [{
                            label: 'SPO2',
                            data: <?php echo json_encode($spo2_values); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }, {
                            label: 'FC',
                            data: <?php echo json_encode($fc_values); ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                type: 'time',
                                time: {
                                    displayFormats: {
                                        millisecond: 'MMM DD HH:mm:ss',
                                        second: 'MMM DD HH:mm:ss',
                                        minute: 'MMM DD HH:mm',
                                        hour: 'MMM DD HH:mm',
                                        day: 'MMM DD',
                                        week: 'MMM DD',
                                        month: 'MMM YYYY',
                                        quarter: 'MMM YYYY',
                                        year: 'YYYY'
                                    }
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            </script>
            <?php
        } else {
            echo "Aucun résultat trouvé pour l'id_patient : $id_patient_selected.";
        }
    }

    // Fermeture de la connexion
    $connexion->close();
    ?>
</body>
</html>
