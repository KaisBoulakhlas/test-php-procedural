<?php 

$conn = mysqli_connect("localhost","root","","test");

if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}

$stmt_moyennes = mysqli_query($conn, "SELECT e.nom, e.prenom, ROUND(AVG(n.note * m.coefficient) / AVG(m.coefficient), 2) AS moyenne 
                                    FROM etudiants e 
                                    INNER JOIN notes n 
                                    ON n.etudiant_id = e.id 
                                    INNER JOIN modules m
                                    ON m.id = n.module_id
                                    GROUP BY e.id 
                                    ORDER BY moyenne DESC;");

$moyennes = array();

while($rowMoyenne = mysqli_fetch_assoc($stmt_moyennes)) {
    array_push($moyennes, $rowMoyenne);
}

mysqli_close($conn);
                                
?>

<!DOCTYPE html>
<html>
<head>
	<title>Liste des moyennes</title>
</head>
<body>
	<table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Moyenne</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($moyennes as $moyenne): ?>
            <tr>
                <td><?= $moyenne['nom'] ?></td>
                <td><?= $moyenne['prenom'] ?></td>
                <td><?= $moyenne['moyenne'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>