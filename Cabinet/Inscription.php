<?php
session_start();
require "connexion.php"; // Changez ceci par le chemin correct vers votre script de connexion

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fonction'])) {
    $con->begin_transaction();

    try {
        $requiredFields = $_POST['fonction'] == "Medecin" ? 
            ['numSecu', 'nom', 'prenom', 'dateNaissance', 'sexe', 'adresse', 'codePostal', 'ville', 'telephone', 'email', 'password', 'confirmPassword'] : 
            ['numCPS', 'nomMedecin', 'prenomMedecin', 'dateNaissanceMedecin', 'sexeMedecin', 'adresseMedecin', 'codePostalMedecin', 'villeMedecin', 'telephoneMedecin', 'emailMedecin', 'passwordMedecin', 'confirmPasswordMedecin'];
        
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Le champ $field est requis.");
            }
        }

        if ($_POST['fonction'] == "Patient" && $_POST['password'] !== $_POST['confirmPassword']) {
            throw new Exception("Les mots de passe ne correspondent pas.");
        } elseif ($_POST['fonction'] == "Medecin" && $_POST['passwordMedecin'] !== $_POST['confirmPasswordMedecin']) {
            throw new Exception("Les mots de passe du médecin ne correspondent pas.");
        }

        if ($_POST['fonction'] == "Patient") {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO patient (NumSecu, Nom, Prenom, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssss", $_POST['numSecu'], $_POST['nom'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['sexe'], $_POST['adresse'], $_POST['codePostal'], $_POST['ville'], $_POST['telephone'], $_POST['email'], $hashedPassword);
        } else {
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
    </style>
</head>
<body>
<form method="POST" action="process_inscription.php">
    <h2>INSCRIPTION</h2>
    <label>Fonction</label>
    <div>
      <select name="fonction" id="fonction" required>
        <option value="">Sélectionner la fonction</option>
        <option value="Patient">Patient</option>
        <option value="Medecin">Médecin</option>
        </select>  
    </div>
    

    <!-- Champs spécifiques pour les patients -->
    <div id="fieldsPatient" style="display: none;">
        <input type="text" placeholder="Numéro de sécurité sociale" name="numSecu" required>
        <input type="text" placeholder="Nom" name="nom" required>
        <input type="text" placeholder="Prénom" name="prenom" required>
        <label for="date">Date de naissance:</label>
        <input type="date" placeholder="Date de naissance" name="dateNaissance" required>
        <label for="Sexe">Sexe:</label>
        <select name="sexe" required>
            <option value="">Sélectionner le sexe</option>
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
    </div>

    <!-- Champs spécifiques pour les médecins -->
    <div id="fieldsMedecin" style="display: none;">
        <!-- Champs communs -->
        <input type="text" placeholder="Numéro CPS" name="numCPS" required>
        <input type="text" placeholder="Nom" name="nomMedecin" required>
        <input type="text" placeholder="Prénom" name="prenomMedecin" required>
        <label for="dateNaissance">Date de naissance:</label>
    <input type="date" id="dateNaissance" name="dateNaissance" required>
    <label for="sexe">Sexe:</label>
    <select name="sexe" id="sexe" required>
        <option value="">Sélectionner le sexe</option>
        <option value="M">Homme</option>
        <option value="F">Femme</option>
    </select>
        <input type="text" placeholder="Adresse" name="adresseMedecin" >
        <input type="text" placeholder="Code Postal" name="codePostalMedecin" >
        <input type="text" placeholder="Ville" name="villeMedecin" >
        <input type="tel" placeholder="Téléphone" name="telephoneMedecin" required>
        <input type="email" placeholder="Adresse e-mail" name="emailMedecin" required>
        <input type="password" placeholder="Mot de passe" name="passwordMedecin" required>
        <input type="password" placeholder="Confirmation du mot de passe" name="confirmPasswordMedecin" required>
    </div>
    <div>
        <a href="inscriptionPatient.php">Patient</a>
        <a href="inscriptionMedecin.php">Medecin</a>
    </div>
    <div>
        <input type="submit" value="S'inscrire"/>
    </div>
    <p>Vous avez déjà un compte ? <a href="Authentification.php">Connectez-vous ici.</a></p>
</form>

<script>
    document.getElementById('fonction').addEventListener('change', function() {
        var fieldsPatient = document.getElementById('fieldsPatient');
        var fieldsMedecin = document.getElementById('fieldsMedecin');

        // Cachez tous les champs au départ
        fieldsPatient.style.display = 'none';
        fieldsMedecin.style.display = 'none';

        // Affichez les champs en fonction de la fonction sélectionnée
        if (this.value === 'Patient') {
            fieldsPatient.style.display = 'block';
        } else if (this.value === 'Medecin') {
            fieldsMedecin.style.display = 'block';
        }
    });
</script>
</body>
</html>
