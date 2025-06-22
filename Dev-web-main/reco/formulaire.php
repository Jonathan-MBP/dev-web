<?php 
require_once "_menu.php";
?>

<!-- formulaire.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de candidature</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            margin-top: 60px;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary">🎓 Formulaire de candidature</h2>
    <form action="traitement_formulaire.php" method="post" enctype="multipart/form-data">
        <!-- Prénom -->
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" name="prenom" id="prenom" class="form-control" required>
        </div>

        <!-- Nom -->
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>

        <!-- Âge -->
        <div class="mb-3">
            <label for="age" class="form-label">Âge</label>
            <input type="number" name="age" id="age" class="form-control" required>
        </div>

        <!-- Ville d'étude -->
        <div class="mb-3">
            <label for="ville" class="form-label">Ville d’étude</label>
            <select name="ville" id="ville" class="form-select" required>
                <option value="">Choisissez une ville</option>
                <option value="Abidjan">Abidjan</option>
                <option value="Barcelone">Barcelone</option>
                <option value="Genève">Genève</option>
                <option value="Lausanne">Lausanne</option>
                <option value="Londres">Londres</option>
                <option value="Monaco">Monaco</option>
                <option value="Munich">Munich</option>
            </select>
        </div>

        <!-- Pièce d'identité -->
        <div class="mb-3">
            <label for="piece_identite" class="form-label">Pièce d'identité (PDF ou image)</label>
            <input type="file" name="piece_identite" id="piece_identite" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>

        <!-- Bulletins scolaires -->
        <div class="mb-3">
            <label for="bulletin" class="form-label">Bulletins scolaires (PDF)</label>
            <input type="file" name="bulletin" id="bulletin" class="form-control" accept=".pdf" required>
        </div>

        <!-- Bouton envoyer -->
        <div class="text-center">
            <button type="submit" class="btn btn-success">Envoyer la candidature</button>
        </div>
    </form>
</div>

</body>
</html>
