<?php
//connexion à la base de données
$con = new PDO("mysql:host=localhost;dbname=gest_presence", "root", "");
if (isset($_POST['btn1'])){
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero = $_POST['tel'];
    $marqueadress = $_POST['marquetel'];
    // préparation de la requete d'insertion
    $insert = $con->prepare("INSERT INTO compte (nom, prenom, numero, marqueadress) VALUES (:nom, :prenom, :numero, :marqueadress)");
    $insert->bindParam(':nom', $nom);
    $insert->bindParam(':prenom', $prenom);
    $insert->bindParam(':numero', $numero);
    $insert->bindParam(':marqueadress', $marqueadress);
    // vérification après l'insertion dans la base de données
        if ($insert->execute()) {
            echo "Utilisateur créé avec succès!";
        } else {
            echo "Erreur lors de l'insertion dans la base de données : " . $insert->errorInfo()[2];
        }
}
?>
 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Dashbord</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <nav>
        <a href="presence.php">Vérification des présences</a>
        <a href="dashbord.php">Nouveau personnel</a>
    </nav>
    <div id="form1">
        <h3>Compte/Administrateur</h3>
        <form action="" method="POST">
            <input type="text" name="nom" placeholder="Entrez le nom" required/><br/>
            <input type="text" name="prenom" placeholder="Entrez le prenom" required/><br/>
            <input type="text" name="tel" placeholder="Entrez le numéro de téléphone" required/><br/>
            <input type="text" name="marquetel" placeholder="Entrez  l'adresse marque" required/><br/>
            <input type="submit" name="btn1" value="Envoyer"/>
        </form>
    </div>
    <div id="tab">
        <table border="1px">
            <tr>
                <th>Id</th>
                <th>Nom et Prénoms</th>
                <th>Phone Number</th>
                <th>MarqueAdresse</th>
            </tr>
            <?php
                $select = $con->query("SELECT * FROM compte");
                foreach($select as $value){
                    echo "<tr>
                    <td>".$value['id']."</td>
                    <td>".$value['nom'].' '.$value['prenom']."</td>
                    <td>".$value['numero']."</td>
                    <td>".$value['marqueadress']."</td>
                </tr>";
                }
            ?>
        </table>
    </div>
</body>
</html>