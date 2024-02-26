<?php
session_start();
require "connexion.php"; // Changez ceci par le chemin correct vers votre script de connexion

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fonction'])) {
    $con->begin_transaction();

    try {
        $requiredFields = $_POST['fonction'] == "Patient" ? 
            ['numSecu', 'nom', 'prenom', 'dateNaissance', 'telephone', 'email', 'password', 'confirmPassword'] : 
            ['numSecu', 'nom', 'prenom', 'dateNaissance', 'telephone', 'email', 'password', 'confirmPassword'];        
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
            $stmt = $con->prepare("INSERT INTO patient (NumSecu, Nom_Pat, Prenom_Pat, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssss", $_POST['numSecu'], $_POST['nom'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['sexe'], $_POST['adresse'], $_POST['codePostal'], $_POST['ville'], $_POST['telephone'], $_POST['email'], $hashedPassword);
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
