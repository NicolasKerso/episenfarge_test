<?php
if (session_id() == '') session_start(); 
if (isset($_SESSION['NumSecu']) == false) {
    header("location: Authentification.php");
    exit();
} 
$userName = $_SESSION['NumSecu'];
$erreur = "";
if (isset($_POST['btn']) == true) {
    $ancienpassword = $_POST['ancienpassword'];
    $nouveaupassword = $_POST['nouveaupassword'];
    $confirnouveaupassword = $_POST['confirnouveaupassword'];

    $conn = new PDO("mysql:host=localhost;dbname=u677866956_test;charset=utf8", "u677866956_compte_test", ";-k33vLYw:H9");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
    $sql ="SELECT * FROM patient WHERE NumSecu = ?";
    $stmt = $conn->prepare($sql); 
    $stmt->execute([$userName]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($ancienpassword, $user['Password'])) {
        $erreur .= "L'ancien mot de passe est erroné<br>";
    }
    if (strlen($nouveaupassword)<6) {$erreur.="Le nouveau mot de passe est trop court<br>";}
    if ($ancienpassword === $nouveaupassword) {
        $erreur .= "Le nouveau mot de passe ne doit pas être identique à l'ancien mot de passe<br>";
    }
    if ($nouveaupassword != $confirnouveaupassword) {$erreur.="La confirmation du nouveau mot de passe n'est pas identique<br>"; }
    if ($erreur == "") {
        $nouveaupasswordHash = password_hash($nouveaupassword, PASSWORD_DEFAULT);
        $sql = "UPDATE patient SET Password = ?, first_login = 0 WHERE NumSecu = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->execute([$nouveaupasswordHash, $userName]);
        echo "Mot de passe mis à jour";
        header('Location: index.php');
        exit();
    }
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


<form method="POST" action="#" id="verification_form">
    <b><h3 style="color: red" class="text-center">
    <?php if ($erreur != "") { ?>
        <div class="alert alert-info"><?php echo $erreur ?> </div>
    <?php } ?>
    </h3></b>
    <h2> CHANGEMENT DU MOT DE PASSE </h2>
    <label>Nom d'utilisateur</label>
    <input type="text" value="<?php echo $_SESSION['NumSecu'];?>" id="username" name="username" required="required" disabled>
    <label>Ancien mot de passe</label>
    <input value="<?php if(isset($ancienpassword) == true) echo $ancienpassword; ?>" type="password" placeholder="Ancien mot de passe" id="ancienpassword" name="ancienpassword" required="required">  
    <label>Nouveau mot de passe</label>
    <input value="<?php if(isset($nouveaupassword) == true) echo $nouveaupassword; ?>" type="password" placeholder="Nouveau mot de passe" id="nouveaupassword" name="nouveaupassword" required="required">  
    <label>Confirmation du nouveau mot de passe</label>
    <input value="<?php if(isset($confirnouveaupassword) == true) echo $confirnouveaupassword; ?>" type="password" placeholder="Confirmation mot de passe" id="confirnouveaupassword" name="confirnouveaupassword" required="required">  
    <!-- <label>Fonction</label>
    <select name="fonction" id="fonction" required="required">
        <option value="">Sélectionner</option>
        <option value="Patient">Patient</option>
        <option value="Medecin">Médecin</option>
        <option value="Secretaire">Secrétaire</option>
    </select> -->
    <input name="btn" type="submit" value="Changer le mot de passe">
    <!-- <p>Vous n'avez pas de compte ? <a href="Inscription.php">Inscrivez-vous ici.</a></p> -->


</form>
<script>
    function showVerificationPopup() {
        var code = prompt("Entrez le code de vérification :", "");
        if (code != null) {
            document.getElementById("verification_code").value = code;
            document.getElementById("verification_form").submit();
        }
    }
</script>
</body>
</html>
