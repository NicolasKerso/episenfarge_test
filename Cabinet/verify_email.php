<?php
include "connexion.php";

// Obtenez l'e-mail et le nom d'utilisateur de la demande POST
$email = $_POST['email'];
$username = $_POST['username'];

// Construisez la requête SQL pour vérifier l'e-mail et le nom d'utilisateur
$query = "SELECT `email` FROM `users` WHERE `UserName` = '" . htmlspecialchars(str_replace("'", "`", $username)) . "' LIMIT 1";

// Exécutez la requête et obtenez le résultat
$result = mysqli_query($con, $query);

// Vérifiez si la requête a réussi
if ($result) {
    // Obtenez la ligne de résultat
    $row = mysqli_fetch_assoc($result);

    // Vérifiez si l'e-mail dans la base de données correspond à l'e-mail entré
    if ($row['email'] == $email) {
        echo "OK";
    } else {
        echo "Email does not match. Database has: " . $row['email'] . ", Received: " . $email;
    }
} else {
    echo "SQL Query failed. Error: " . mysqli_error($con);
}
?>
