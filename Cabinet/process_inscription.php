<?php
session_start();
require "connexion.php"; // Changez ceci par le chemin correct vers votre script de connexion

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fonction'])) {
    $con->begin_transaction();

    try {
        $requiredFields = $_POST['fonction'] == "Patient" ? 
            ['numSecu', 'nom', 'prenom', 'dateNaissance', 'telephone', 'email', 'password', 'confirmPassword', 'numerobracelet'] : 
            ['numSecu', 'nom', 'prenom', 'dateNaissance', 'telephone', 'email', 'password', 'confirmPassword', 'numerobracelet'];        
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Le champ $field est requis.");
            }
        }

        if ($_POST['fonction'] == "Patient" && $_POST['password'] !== $_POST['confirmPassword']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        } 
        if ($_POST['fonction'] == "Patient") {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO patient (NumSecu, Nom_Pat, Prenom_Pat, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, Password, numerobracelet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $_POST['numSecu'], $_POST['nom'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['sexe'], $_POST['adresse'], $_POST['codePostal'], $_POST['ville'], $_POST['telephone'], $_POST['email'], $hashedPassword,$_POST['numerobracelet']);
        }
        if ($_POST['fonction'] == "Medecin" && $_POST['password'] !== $_POST['confirmPassword']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        } 
        if ($_POST['fonction'] == "Medecin") {
            $hashedPasswordMedecin = password_hash($_POST['passwordMedecin'], PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO medecin (NumCPS, Nom, Prenom, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssi", $_POST['numCPS'], $_POST['nomMedecin'], $_POST['prenomMedecin'], $_POST['dateNaissanceMedecin'], $_POST['sexeMedecin'], $_POST['adresseMedecin'], $_POST['codePostalMedecin'], $_POST['villeMedecin'], $_POST['telephoneMedecin'], $_POST['emailMedecin'], $hashedPasswordMedecin);
        }
        
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Erreur lors de l'insertion : " . $stmt->error);
        }


        $userId = $con->insert_id;

        $con->commit();


        $_SESSION['userId'] = $userId;
        $_SESSION['fonction'] = $_POST['fonction'];
        header('Location: /Cabinet/Authentification.php');
        exit();

    } catch (Exception $e) {

        $con->rollback();
        $message = "Erreur d'inscription : " . $e->getMessage();
    }
}

if ($message) {
    echo "<div class='error'>$message</div>";
}
?>
