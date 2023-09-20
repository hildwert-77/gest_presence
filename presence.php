<?php
    //connexion à la base de données
    $con = new PDO("mysql:host=localhost;dbname=gest_presence", "root", "");
    $choix="0";
    if(isset($_POST['btn']))
    {
        $choix = $_POST['filtre'];
    }
    $select = $con->query("SELECT nom, prenom, datetimee, retard, DATE(datetimee) as dat FROM compte, arrivee WHERE compte.numero = arrivee.numero");
    
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
    <div class="form">
        <form action="presence.php" method="POST">
            <select name="filtre">
                <option selected disabled>Choisir une option</option>
                <option value="1">Tous les jours</option>
                <option value="2">Aujourd'hui</option>
            </select>
            <input type="submit" value="Lister" name="btn">
        </form>
    </div>
    <div id="tab">
        <?php
            if($choix=="1")
            {
                echo'<h3>Historique complet de présence</h3>';
            }
            else{
                echo'<h3>Historique de présence du jour</h3>';

            }
        ?>
        <table border="1">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date et heure</th>
                <th>statut</th>
            </tr>
            <?php
                    $dateSysteme = date("Y-m-d");
                    foreach ($select as $value) {
                        if($choix=="1")
                        {
                            echo "
                                <tr>
                                    <td>".$value['nom']."</td>
                                    <td>".$value['prenom']."</td>
                                    <td>".$value['datetimee']."</td>
                                    <td>".$value['retard']."</td>
                                </tr>
                            ";
                        }
                        else{
                            if($value['dat']==$dateSysteme)
                            {
                                echo "
                                    <tr>
                                        <td>".$value['nom']."</td>
                                        <td>".$value['prenom']."</td>
                                        <td>".$value['datetimee']."</td>
                                        <td>".$value['retard']."</td>
                                    </tr>
                                ";
                            } 
                        }
                        
                    }
                ?>
        </table>
    </div>
</body>
</html>