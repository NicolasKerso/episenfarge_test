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
            $stmt = $con->prepare("INSERT INTO patient (NumSecu, Nom_Pat, Prenom_Pat, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, Password,numerobracelet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $_POST['numSecu'], $_POST['nom'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['sexe'], $_POST['adresse'], $_POST['codePostal'], $_POST['ville'], $_POST['telephone'], $_POST['email'], $hashedPassword, $_POST['numerobracelet']);
        }
        
        if ($_POST['fonction'] == "Medecin" && $_POST['passwordMedecin'] !== $_POST['confirmPasswordMedecin']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        } 
        if ($_POST['fonction'] == "Medecin") {
            $hashedPasswordMedecin = password_hash($_POST['passwordMedecin'], PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO medecin (NumCPS, Nom, Prenom, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssss", $_POST['numCPS'], $_POST['nomMedecin'], $_POST['prenomMedecin'], $_POST['dateNaissanceMedecin'], $_POST['sexeMedecin'], $_POST['adresseMedecin'], $_POST['codePostalMedecin'], $_POST['villeMedecin'], $_POST['telephoneMedecin'], $_POST['emailMedecin'], $hashedPasswordMedecin);
        }
      
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Erreur lors de l'insertion : " . $stmt->error);
        }


        $userId = $con->insert_id;

        $con->commit();


        $_SESSION['userId'] = $userId;
        $_SESSION['fonction'] = "Secretaire";
        header('Location: index2.php');
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
<!DOCTYPE html>
<html>
<head>
    <title></title>

    <style type="text/css">
        html, body {
            background: linear-gradient(120deg, #72e99f 0%, #8fd3f4 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        *{
            font-family: 'Roboto', sans-serif;
            box-sizing: border-box;
        }

        form {
            width: 500px;
            border: 2px solid #ccc;
            padding: 30px;
            background: #fff;
            border-radius: 60px;
            box-shadow: 0px 10px 20px rgba(0,0,0,0.19), 0px 6px 6px rgba(0,0,0,0.23);
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        input {
            display: block;
            border: 2px solid #ccc;
            width: 95%;
            padding: 10px;
            margin: 10px auto;
            border-radius: 15px;
            outline: none;
        }
        label {
            color: #666;
            font-size: 18px;
            padding: 10px;
            border-radius: 30px;
        }

        button {
            float: right;
            background: #72e99f;
            padding: 10px 15px;
            color: #fff;
            border-radius: 15px;
            margin-right: 10px;
            border: none;
            cursor: pointer;
            transition: background 0.5s;
        }
        button:hover{
            background: #8fd3f4;
        }
        .error {
            background: #F2DEDE;
            color: #A94442;
            padding: 10px;
            width: 95%;
            border-radius: 5px;
            margin: 20px auto;
        }

        h1 {
            text-align: center;
            color: #fff;
        }

        a {
            float: right;
            background: #84fab0;
            padding: 10px 15px;
            color: #fff;
            border-radius: 5px;
            margin-right: 10px;
            border: none;<style type="text/css">
                                               html, body {
                                                   background: linear-gradient(120deg, #72e99f 0%, #8fd3f4 100%);
                                                   display: flex;
                                                   justify-content: center;
                                                   align-items: center;
                                                   height: 100vh;
                                                   flex-direction: column;
                                               }

        *{
            font-family: 'Roboto', sans-serif;
            box-sizing: border-box;
        }

        form {
            width: 500px;
            border: 2px solid #ccc;
            padding: 30px;
            background: #fff;
            border-radius: 60px;
            box-shadow: 0px 10px 20px rgba(0,0,0,0.19), 0px 6px 6px rgba(0,0,0,0.23);
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        input {
            display: block;
            border: 2px solid #ccc;
            width: 95%;
            padding: 10px;
            margin: 10px auto;
            border-radius: 15px;
            outline: none;
        }
        label {
            color: #666;
            font-size: 18px;
            padding: 10px;
            border-radius: 30px;
        }

        button {
            float: right;
            background: #72e99f;
            padding: 10px 15px;
            color: #fff;
            border-radius: 15px;
            margin-right: 10px;
            border: none;
            cursor: pointer;
            transition: background 0.5s;
        }
        button:hover{
            background: #8fd3f4;
        }
        .error {
            background: #F2DEDE;
            color: #A94442;
            padding: 10px;
            width: 95%;
            border-radius: 5px;
            margin: 20px auto;
        }

        h1 {
            text-align: center;
            color: #fff;
        }

        a {
            float: right;
            background: #84fab0;
            padding: 10px 15px;
            color: #fff;
            border-radius: 5px;
            margin-right: 10px;
            border: none;
            text-decoration: none;
            transition: background 0.5s;
        }
        a:hover{
            background: #8fd3f4;
        }

        text-decoration: none;
        transition: background 0.5s;
        }
        a:hover{
            background: #8fd3f4;
        }
    </style>
</head>





<body>
    <p>Vous etes bien inscrit! <a href="Authentification.php">Inscrivez-vous ici.</a></p>
    
</body>
</html>
