<?php session_start();?>
<?php
$message= "";

if(isset($_POST['password']) and isset($_POST['username']))
{
    include "connexion.php";
    $queryAuth = "SELECT `Id_user`, `UserName`, `Password`, `Specialite`, `FonctionL`, `email` FROM `users`
     WHERE UserName = '" . htmlspecialchars(str_replace("'", "`", $_POST['username'])) . "' 
     AND FonctionL = '" . $_POST['fonction'] . "'";

    $resultAth=mysqli_query($con,$queryAuth);
    $rowAuh = $resultAth->fetch_assoc();

    if ($rowAuh && password_verify($_POST['password'], $rowAuh['Password'])) {
        $_SESSION['id_user']=$rowAuh['Id_user'];
        $_SESSION['Username']=$rowAuh['UserName'];
        $_SESSION['Specialite']=$rowAuh['Specialite'];
        $_SESSION['Fonction']=$rowAuh['FonctionL']; // Ici on ajoute la fonction à la session


        switch ($_SESSION['Fonction']) {
            case 'Patient':
                header('Location: /Cabinet/index.php');
                exit;
            case 'Medecin':
                header('Location: /Cabinet/index1.php');
                exit;
            case 'Secretaire':
                header('Location: /Cabinet/index2.php');
                exit;
            default:
                $message = "Fonction invalide";
                break;
        }


    } else {
        $message = "Nom d'utilisateur ou mot de passe incorrect";
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
    <h2> LOGIN </h2>
    <label>Nom d'utilisateur</label>
    <input type="text" placeholder="Utilisateur" id="username" name="username" required="required">
    <label>Mot de passe</label>
    <input type="password" placeholder="mot de passe" id="password" name="password" required="required">  <b><h3 style="color: red"><?php echo $message;?> </h3></b>
    <label>Fonction</label>
    <select name="fonction" id="fonction" required="required">
        <option value="">Sélectionner</option>
        <option value="Patient">Patient</option>
        <option value="Medecin">Médecin</option>
        <option value="Secretaire">Secrétaire</option>
    </select>
    <input type="submit" value="se connecter">
    <p>Vous n'avez pas de compte ? <a href="Inscription.php">Inscrivez-vous ici.</a></p>


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