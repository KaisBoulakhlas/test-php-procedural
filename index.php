<?php 

$conn = mysqli_connect("localhost","root","","test"); 

if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}

$stmt_etudiants = mysqli_query($conn, "SELECT * FROM etudiants");
$stmt_modules = mysqli_query($conn, "SELECT * FROM modules");

$etudiants = array();
$modules = array();

while($rowEtudiant = mysqli_fetch_assoc($stmt_etudiants)) {
    array_push($etudiants, $rowEtudiant);
}

while($rowModules = mysqli_fetch_assoc($stmt_modules)) {
    array_push($modules, $rowModules);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Récupération des données du formulaire
    $etudiant = mysqli_real_escape_string($conn, $_POST['etudiant']);
    $module = mysqli_real_escape_string($conn, $_POST['module']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    // Insertion des données dans la base de données avec une requête préparée
    $stmt = mysqli_prepare($conn, "INSERT INTO notes (etudiant_id, module_id, note) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iid", $etudiant, $module, $note);

    if (mysqli_stmt_execute($stmt)) {
        echo "Note enregistrée avec succès";
    } else {
        echo "Erreur : " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
}
// Fermeture de la connexion à la base de données

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Formulaire de saisie des notes</title>
</head>
<body>
	<h1>Formulaire de saisie des notes</h1>
	<form action="#" method="post" id="form">
		<label for="etudiant">Étudiant :</label>
		<select name="etudiant" id="etudiant">
            <?php foreach($etudiants as $etudiant): ?>
                <option value=<?= $etudiant['id'] ?>><?= $etudiant['nom'] . ' ' . $etudiant['prenom']?></option> 
            <?php endforeach; ?>
		</select><br>

		<label for="module">Module :</label>
		<select name="module" id="module">
            <?php foreach($modules as $module): ?>
                <option value=<?= $module['id'] ?>><?= $module['nom'] ?></option> 
            <?php endforeach; ?>
		</select><br>

		<label for="note">Note :</label>
		<input type="number" step="0.01" name="note" id="note" class="note"><br>

		<button type="submit" name="register" id="register">Enregistrer la note</button>
	</form>
</body>
<script>
        const formInput = document.querySelector("#form");
        let noteInput = document.querySelector("#note");
        formInput.addEventListener("submit", (event) => {
            let note = parseFloat(noteInput.value);
            return isNaN(note) || note < 0 || note > 20 ?  (event.preventDefault(), alert("Veuillez saisir une note valide (comprise entre 0 et 20)")) : null;
        })
	</script>
</html>
