<?php
session_start();
require "connexion.php"; // Assurez-vous que le chemin d'accès est correct

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
        $stmt = $con->prepare("INSERT INTO patient (NumSecu, Nom, Prenom, DateNaissance, Sexe, Adresse, CodePostal, Ville, Telephone, Email, numerobracelet, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $_POST['numSecu'], $_POST['nom'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['sexe'], $_POST['adresse'], $_POST['codePostal'], $_POST['ville'], $_POST['telephone'], $_POST['email'], $_POST['numerobracelet'], $hashedPassword);

        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Erreur lors de l'insertion : " . $stmt->error);
        }

        $_SESSION['userId'] = $con->insert_id;
        $_SESSION['fonction'] = $_POST['fonction'];

        $con->commit();
        
        header('Location: Authentification.php');
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
    <!-- Styles et autres métadonnées ici -->
</head>
<body>
<form method="POST" action="">
    <h2>INSCRIPTION</h2>
    <select name="fonction" id="fonction" required>
        <option value="Patient">Patient</option>
    </select> 

    <div id="fieldsPatient" style="display: block;">
        <input type="text" placeholder="Numéro de sécurité sociale" name="numSecu" required pattern="\d{13}" title="Le numéro de sécurité sociale doit contenir exactement 13 chiffres.">
        <!-- Autres champs ici -->
        <input type="text" placeholder="Nom" name="nom" required>
        <input type="text" placeholder="Prénom" name="prenom" required>
        <input type="date" name="dateNaissance" required>
        <select name="sexe" required>
            <option value="M">Homme</option>
            <option value="F">Femme</option>
        </select>
        <input type="text" placeholder="Adresse" name="adresse" required>
        <input type="text" placeholder="Code Postal" name="codePostal" required>
        <input type="text" placeholder="Ville" name="ville" required>
        <input type="tel" placeholder="Téléphone" name="telephone" required>
        <input type="email" placeholder="Adresse e-mail" name="email" required>
        <input type="password" placeholder="Mot de passe" name="password" required>
        <input type="password" placeholder="Confirmation du mot de passe" name="confirmPassword" required>
        <input type="text" placeholder="Numéro du Bracelet" name="numerobracelet" required>
    </div>

    <div class="form-action-buttons">
        <button type="submit">S'inscrire</button>
    </div>
</form>
</body>
</html>
