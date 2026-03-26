<?php

/**
 * edit-project.php
 * ---
 * Permet de changer l'ordre d'apparition des différents médias dans le projet.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */

require_once __DIR__ . "/core/utils.php";

$projectName = $_REQUEST["name"];
$projectDirName = projectNameToDirectoryName($projectName);
$projectDirPath = projectDirectoryPath($projectName);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST["action"]) {
        case "add-element":
            break;
    }

    http_response_code(303);
    header(
        "Location: /edit-project.php?action=edit&name=" .
            urlencode($projectName),
    );
    exit();
} else {
    switch ($_GET["action"]) {
        case "add":
            mkdir($projectDirPath);
            mkdir($projectDirPath . "/audio");
            mkdir($projectDirPath . "/video");
            file_put_contents(
                $projectDirPath . "/project.xml",
                '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><project><title>' .
                    htmlentities($projectName) .
                    "</title><elements></elements></project>",
            );
            header(
                "Location: /edit-project.php?action=edit&name=" .
                    urlencode($projectDirName),
            );
            return;
        case "edit":
            requireValidProjectName($projectName);

            $str = file_get_contents($projectDirPath . "/project.xml");
            $project = new SimpleXMLElement($str);
            $videos = scandir($projectDirPath . "/video");
            $audios = scandir($projectDirPath . "/audio");

            $videos = array_filter($videos, function ($item) {
                return substr($item, 0, 1) != ".";
            });
            $audios = array_filter($audios, function ($item) {
                return substr($item, 0, 1) != ".";
            });
            break;
        default:
            http_response_code(303);
            header("Location: /index.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier <?= htmlspecialchars($project->title) ?> | Régis</title>
    <?php include __DIR__ . "/assets/partials/headlinks.php"; ?>
</head>

<body data-bs-theme="<?= $_COOKIE["bs-theme"] ?? "light" ?>">
    <?php include __DIR__ . "/assets/partials/header.php"; ?>

    <section class="container">
        <h2>Modifier le projet "<?= htmlspecialchars($project->title) ?>"</h2>
        <a href="/edit-medias.php?name=<?= urlencode(
            $_GET["name"],
        ) ?>" class="btn btn-primary">Modifier les médias du projet</a>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addElementModal">
            Ajouter un élément
        </button>
    </section>

    <section class="container">
        <?php foreach ($project->elements->element as $element): ?>
            <div>Element here</div>
        <?php endforeach; ?>
        <?php if (empty($project->elements->element)): ?>
            <div class="alert alert-info">Aucun élément ajouté pour le moment.</div>
        <?php endif; ?>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="addElementModal" tabindex="-1" aria-labelledby="addElementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addElementModalLabel">Ajouter un élément</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="srcSelect" class="form-label">Fichier à utiliser</label>
                            <select class="form-select" id="srcSelect" name="src">
                                <optgroup label="Vidéos">
                                    <?php foreach ($videos as $video): ?>
                                        <option value="video/<?= htmlspecialchars(
                                            $video,
                                        ) ?>">
                                            <?= htmlspecialchars($video) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Audios">
                                    <?php foreach ($audios as $audio): ?>
                                        <option value="audio/<?= htmlspecialchars(
                                            $audio,
                                        ) ?>">
                                            <?= htmlspecialchars($audio) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sceneInput" class="form-label">Numéro de la scène</label>
                            <input type="text" class="form-control" id="sceneInput" name="scene" required>
                        </div>

                        <div class="mb-3">
                            <label for="titleInput" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titleInput" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="descriptionInput" class="form-label">Description</label>
                            <textarea class="form-control" id="descriptionInput" name="description" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="container mt-3">
            <pre><?php print_r($project); ?></pre>
        </div>
    </section>

    <?php include __DIR__ . "/assets/partials/footer.php"; ?>
</body>

</html>
