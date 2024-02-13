<?php
include "../connexion.php";
$date1 = strtr($_POST['DateNaissance'], '/', '-');
$dateN=	date('Y/m/d',strtotime($date1));
$password = substr(md5(mt_rand()), 0, 7); // Génère un mot de passe aléatoire

$stmt = mysqli_stmt_init($con);

if($_POST['type']=='Insert')
{
    $queryP = "INSERT INTO patient (CIN, Nom_Pat, Prenom_Pat, DateNaissance, Telephone, Adresse, Email, Sexe, DateCreation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";

    if (!mysqli_stmt_prepare($stmt, $queryP)) {
        die('Erreur de préparation de requête : ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_bind_param($stmt, "ssssssss", $_POST['CIN'], $_POST['NomP'], $_POST['PrenomPa'], $dateN, $_POST['telephone'], $_POST['Adreese'], $_POST['Email'], $_POST['SexeP']);

    if (!mysqli_stmt_execute($stmt)) {
        die('Erreur d\'exécution de requête : ' . mysqli_stmt_error($stmt));
    }

    $last_id = mysqli_insert_id($con); // Obtient le dernier identifiant inséré (Id_pat)
    $last_email = $_POST['Email']; // Obtient le dernier email inséré

    $queryUser = "INSERT INTO users (Id_pat, email) VALUES (?, ?)";
    if (!mysqli_stmt_prepare($stmt, $queryUser)) {
        die('Erreur de préparation de requête : ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_bind_param($stmt, "is", $last_id, $last_email);

    if (!mysqli_stmt_execute($stmt)) {
        die('Erreur d\'exécution de requête : ' . mysqli_stmt_error($stmt));
    }
}

else if($_POST['type']=='Delete')
{
    $queryP = "DELETE FROM patient WHERE Id_pat = ?";
    if (mysqli_stmt_prepare($stmt, $queryP)) {
        mysqli_stmt_bind_param($stmt, "i", $_POST['idPas']);
        mysqli_stmt_execute($stmt);
    }
}
else if($_POST['type']=='update')
{
    $queryP = "UPDATE patient SET CIN = ?, Nom_Pat = ?, Prenom_Pat = ?, DateNaissance = ?, Telephone = ?, Adresse = ?, Email = ?, Sexe = ? WHERE Id_pat = ?";

    if (mysqli_stmt_prepare($stmt, $queryP)) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $_POST['CIN'], $_POST['NomP'], $_POST['PrenomPa'], $dateN, $_POST['telephone'], $_POST['Adreese'], $_POST['Email'], $_POST['SexeP'], $_POST['idPas']);
        mysqli_stmt_execute($stmt);
    }
}

echo $dateN;

mysqli_close($con);
?>
