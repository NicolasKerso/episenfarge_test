<<?php
session_start();
include "connexion.php";

$message= "";

if(isset($_POST['username']) and isset($_POST['password']) and isset($_POST['email']) and isset($_POST['fonction']))
{
    // Commencer la transaction
    $con->begin_transaction();

    try {
        $patientId = NULL;

        // Si la fonction est 'Patient', insérer dans la table 'patient' d'abord
        if($_POST['fonction'] == "Patient" && isset($_POST['cin']) and isset($_POST['nom']) and isset($_POST['prenom']) and isset($_POST['dateNaissance']) and isset($_POST['telephone']) and isset($_POST['adresse']) and isset($_POST['dateCreation']) and isset($_POST['sexe'])){

            // Préparer la requête d'insertion dans la table 'patient'
            $queryPatient = $con->prepare("INSERT INTO `patient` (`CIN`, `Nom_Pat`, `Email`,`Prenom_Pat`, `DateNaissance`, `Telephone`, `Adresse`, `DateCreation`, `Sexe`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $queryPatient->bind_param('sssssssss', $_POST['cin'], $_POST['nom'], $_POST['email'], $_POST['prenom'], $_POST['dateNaissance'], $_POST['telephone'], $_POST['adresse'], $_POST['dateCreation'], $_POST['sexe']);

            // Exécuter la requête
            $queryPatient->execute();
            if($queryPatient->error){
                throw new Exception($queryPatient->error);
            }

            // Obtenir l'ID du patient inséré
            $patientId = $con->insert_id;
        }

        // Préparer la requête d'insertion dans la table 'users'
        // Supposons que votre table 'users' ait une colonne 'Id_Pat' pour stocker l'ID du patient
        $queryRegister = $con->prepare("INSERT INTO `users` (`UserName`, `Password`, `email`,`Specialite`,`FonctionL`, `Id_Pat`) VALUES (?, ?, ?, ?, ?, ?)");
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $queryRegister->bind_param('sssssi', $_POST['username'], $hashedPassword, $_POST['email'], $_POST['specialite'], $_POST['fonction'], $patientId);

        // Exécuter la requête
        $queryRegister->execute();
        if($queryRegister->error){
            throw new Exception($queryRegister->error);
        }

        $_SESSION['Username']=$_POST['username'];
        $_SESSION['Fonction']=$_POST['fonction'];

        $con->commit();
        header('Location: /Cabinet/Authentification.php');

    } catch(Exception $e){
        $con->rollback();
        $message = "Il y a eu un problème avec votre inscription. Erreur : " . $e->getMessage();
    }
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
<form method="POST" action="#">
    <h2> INSCRIPTION </h2>
    <label>Nom d'utilisateur</label>
    <input type="text" placeholder="Utilisateur" id="username" name="username" required="required">
    <label>Email</label>
    <input type="email" placeholder="Email" id="email" name="email" required="required">
    <label>Mot de passe</label>
    <input type="password" placeholder="mot de passe" id="password" name="password" required="required">
    <label>Spécialité</label>
    <input type="text" placeholder="Spécialité" id="specialite" name="specialite">
    <label>Fonction</label>
    <select name="fonction" id="fonction" required="required">
        <option value="">Sélectionner</option>
        <option value="Patient">Patient</option>
        <option value="Medecin">Médecin</option>
        <option value="Secretaire">Secrétaire</option>
    </select>

    <div id="patientFields" style="display: none;">
        <label>CIN</label>
        <input type="text" placeholder="CIN" id="cin" name="cin">
        <label>Nom</label>
        <input type="text" placeholder="Nom" id="nom" name="nom">
        <label>Prénom</label>
        <input type="text" placeholder="Prénom" id="prenom" name="prenom">
        <label>Date de naissance</label>
        <input type="date" id="dateNaissance" name="dateNaissance">
        <label>Téléphone</label>
        <input type="tel" id="telephone" name="telephone">
        <label>Adresse</label>
        <input type="text" id="adresse" name="adresse">
        <label>Date de création</label>
        <input type="date" id="dateCreation" name="dateCreation">
        <label>Sexe</label>
        <select name="sexe" id="sexe">
            <option value="">Sélectionner</option>
            <option value="M">M</option>
            <option value="F">F</option>
        </select>
    </div>

    <b><h3 style="color: red"><?php echo $message;?> </h3></b>
    <input type="submit" value="s'inscrire">
    <p>Vous avez déjà un compte ? <a href="Authentification.php">Connectez-vous ici.</a></p>
</form>

<script>
    document.getElementById("fonction").addEventListener("change", function() {
        if (this.value == "Patient") {
            document.getElementById("patientFields").style.display = "block";
        } else {
            document.getElementById("patientFields").style.display = "none";
        }
    });
</script>

</body>
</html>
