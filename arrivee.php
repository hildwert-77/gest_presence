<?php
$con = new PDO("mysql:host=localhost;dbname=gest_presence", "root", "");
function lateness($heure, $min){
    if($heure>0)
    {
        return "Vous êtes en retard de ".$heure."h".$min." min";
    }
    else{
        return "Vous êtes en retard de ".$min." min";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    date_default_timezone_set('Africa/Porto-Novo');
    //$heure = date('H:i:s');
    //var_dump($heure);
    $dateHeureSysteme = date("Y-m-d H:i:s");
    $dateSysteme = date("Y-m-d");
    if (isset($_POST['num'])){
        $num = $_POST['num'];
        $req = $con->prepare("SELECT * FROM compte WHERE numero = ?");
        $req->execute([$num]);
        if($req->rowCount()>0)
        {
            $heure_normale = strtotime('8:30'); //en timetamp
            $dateHeureArrivee = date("Y-m-d H:i:s"); // A ajouter dans la base
            $heureArrivee = date("H:i"); //en string
            $heure_Arrivee = strtotime($heureArrivee); //en timestamp
            
            if($heure_Arrivee > $heure_normale){
                $retard = $heure_Arrivee - $heure_normale; //Calcul du retard en timestamp
                
                $minutesDeRetard = date('i', $retard); // recupération des minutes de retard
                $heuresDeRetard = date('H', $retard); // recupération des heures de retard
                //Appel de la fonction lateness
                $statut = lateness($heuresDeRetard, $minutesDeRetard);
            }
            else{
                $statut = "A l'heure";
            }
            $insert = $con->prepare("INSERT INTO arrivee (numero, datetimee, retard) VALUES (:num, :dateHeure,:retard)");
            $insert->bindParam(':num', $num);
            $insert->bindParam(':dateHeure', $dateHeureArrivee); 
            $insert->bindParam(':retard', $statut);     

            if ($insert->execute()){
                echo "Bienvenue Utilisateur! ".$statut."!";
            } else {
                echo "Erreur lors de l'insertion dans la base de données : " . $insert->errorInfo()[2];
            }
        }
        else{   
                echo'Utilisateur inexistant!';
        }
        
    }
}
$select = $con->query("SELECT nom, prenom, datetimee, DATE(datetimee) as dat, retard FROM compte, arrivee WHERE compte.numero = arrivee.numero");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>arrivee</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <section class="flex-container">
        <div>
            <h3>Enregistrez-vous</h3>
            <div class="nav1">
            <form action="" method="POST">
                <input type="number" name="num" placeholder="Entrez votre numéro" required/>
                <input type="submit" name="btn" value="Valider"/>
            </form>  
        </div>
        <div>
            <table border="1">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date et heure d'arrivée</th>
                    <th>Statut</th>
                </tr>
                <?php
                    $dateSysteme = date("Y-m-d");
                    foreach ($select as $value) {
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
                ?>
            </table>
        </div>
    </section>
</body>
</html>
