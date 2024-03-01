<?php
session_start();
require "connexion.php"; // Changez ceci par le chemin correct vers votre script de connexion

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fonction'])) {
    $con->begin_transaction();

    try {
        $requiredFields = ['numSecu', 'nom', 'prenom', 'dateNaissance', 'sexe', 'adresse', 'codePostal', 'ville', 'telephone', 'email', 'password', 'confirmPassword', 'numerobracelet'];        
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Le champ $field est requis.");
            }
        }

        $numSecu = trim($_POST['numSecu']);
        if (!preg_match('/^\d{13}$/', $numSecu)) {
            throw new Exception("Numéro de sécurité sociale invalide. Il doit contenir exactement 13 chiffres.");
        }

        if ($_POST['password'] !== $_POST['confirmPassword']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        } 

        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $con->prepare("INSERT INTO patient (NumSecu, Nom_Pat, Prenom_Pat, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, numerobracelet, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $_POST['numSecu'], $_POST['nom'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['sexe'], $_POST['adresse'], $_POST['codePostal'], $_POST['ville'], $_POST['telephone'], $_POST['email'], $_POST['numerobracelet'], $hashedPassword);
      
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Erreur lors de l'insertion : " . $stmt->error);
        }

        $_SESSION['userId'] = $con->insert_id;
        $_SESSION['fonction'] = "Secretaire";
        
        $con->commit();
        
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
    <title>Inscription</title>
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
            border: none;
            text-decoration: none;
            transition: background 0.5s;
        }
        a:hover{
            background: #8fd3f4;
        }
        .small-input {
        font-size: 14px; 
        padding: 8px; 
        }
        .top-button {
        margin-top: -20px;
        text-align: center;
        width: 100%;
        }
        .button, .back-button {
            float: right;
            background: #72e99f;
            padding: 10px 15px;
            color: #fff;
            border-radius: 15px;
            margin-right: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none; /* Ajout pour le style du lien comme un bouton */
            transition: background 0.5s;
        }
        .back-button:hover, .button:hover {
            background: #8fd3f4;
        }
        .form-action-buttons {
            overflow: hidden; /* Pour que le flottement des boutons ne perturbe pas le layout extérieur */
            display: block; /* Assure que le conteneur prend toute la largeur */
        }
    </style>
</head>
<body>
<form method="POST" action="">
    <h2>INSCRIPTION</h2>
    <select name="fonction" id="fonction" required>
        <option value="Patient">Patient</option>
    </select> 

    <div id="fieldsPatient" style="display: block;">
        <input type="text" placeholder="Numéro de sécurité sociale" name="numSecu" required pattern="\d{13}" title="Le numéro de sécurité sociale doit contenir exactement 13 chiffres.">
                <input type="text" placeholder="Nom" name="nom" required>
        <input type="text" placeholder="Prénom" name="prenom" required>
        <label for="date" class="small-input">Date de naissance:</label>
        <input type="date" placeholder="Date de naissance" name="dateNaissance" required class="small-input">
        <label for="Sexe" class="small-input">Sexe:</label>
        <select name="sexe" required class="small-input">
            <option value="M">Homme</option>
            <option value="F">Femme</option>
        </select>
        <input type="text" placeholder="Adresse" name="adresse" >
        <input type="text" placeholder="Code Postal" name="codePostal" >
        <input type="text" placeholder="Ville" name="ville" >
        <input type="tel" placeholder="Téléphone" name="telephone" required>
        <input type="email" placeholder="Adresse e-mail" name="email" required>
        <input type="password" placeholder="Mot de passe" name="password" required>
        <input type="password" placeholder="Confirmation du mot de passe" name="confirmPassword" required>
        <input type="text" placeholder="Numéro du Bracelet" name="numerobracelet" required>
    </div>
    <div class="form-action-buttons">
        <button type="submit">S'inscrire</button>
        <a href="Inscription.php" class="back-button">Retour</a
    </div>
</form>
</body>
</html>
