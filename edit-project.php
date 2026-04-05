<?php
/**
 * edit-project.php
 * ---
 * Permet de changer l'ordre d'apparition des différents médias dans le projet.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */

require_once __DIR__ . "/core/autoload.php";

use LesMajesticiels\Regis\Project;
use LesMajesticiels\Regis\ProjectElement;

$projectName = $_REQUEST["name"];
$projectTitle = $_REQUEST["title"] ?? null;
$project = new Project($projectName, $projectTitle);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // L'utilisateur modifie le projet
    switch ($_POST["action"]) {
        case "add-element":
            $elementParameters = [
                "src" => $_POST["src"],
                "type" => mime_content_type($project->getPath($_POST["src"])),
                "scene" => $_POST["scene"],
                "title" => $_POST["title"],
                "description" => $_POST["description"],
                "hint" => $_POST["hint"],
            ];
            $project->addElement(new ProjectElement($elementParameters));
            break;
    }

    http_response_code(303);
    header("Location: /edit-project.php?action=edit&name=" . urlencode($projectName));
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier <?= htmlspecialchars($project->getTitle()) ?> | Régis</title>
    <?php include __DIR__ . "/assets/partials/headlinks.php"; ?>
</head>

<body data-bs-theme="<?= $_COOKIE["bs-theme"] ?? "light" ?>">
    <?php include __DIR__ . "/assets/partials/header.php"; ?>

    <section class="container mb-3">
        <h2>Modifier le projet "<?= htmlspecialchars($project->getTitle()) ?>"</h2>

        <a href="/edit-medias.php?name=<?= urlencode($_GET["name"]) ?>" class="btn btn-primary">
            Modifier les médias du projet
        </a>

        <?php if (!empty($project->getMedias())): ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addElementModal">
                Ajouter un élément
            </button>
        <?php endif; ?>
    </section>

    <section class="container">
        <?php if (empty($project->getElements())): ?>
            <div class="alert alert-info">Aucun élément ajouté pour le moment.</div>
        <?php else: ?>
            <?php foreach ($project->getElements() as $elementIndex => $element): ?>
            <?php $elementArray = $element->__toArray(); ?>
                <div class="d-flex flex-row gap-1 border rounded p-2 mb-2">
                    <div class="flex-grow-1">
                        <strong><?= htmlspecialchars($elementArray["title"]) ?></strong><br>
                        <em>Scène <?= htmlspecialchars($elementArray["scene"]) ?></em><br>
                        <p><?= nl2br(htmlspecialchars($elementArray["description"])) ?></p>
                        <p><?= nl2br(htmlspecialchars($elementArray["hint"])) ?></p>
                    </div>
                    <div class="d-flex flex-row gap-1">
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="move-element-up">
                            <input type="hidden" name="index" value="<?= $elementIndex ?>">
                            <button type="submit" class="btn btn-primary" title="Déplacer vers le haut">
                                <i class="bi bi-arrow-up"></i>
                            </button>
                        </form>
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="move-element-down">
                            <input type="hidden" name="index" value="<?= $elementIndex ?>">
                            <button type="submit" class="btn btn-primary" title="Déplacer vers le bas">
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </form>

                        <form action="" method="POST">
                            <input type="hidden" name="action" value="delete-element">
                            <input type="hidden" name="index" value="<?= $elementIndex ?>">
                            <button type="submit" class="btn btn-danger" title="Supprimer cet élément">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
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
                <form action="" method="POST">
                    <input type="hidden" name="action" value="add-element">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="srcSelect" class="form-label">Fichier à utiliser</label>
                            <select class="form-select" id="srcSelect" name="src">
                                <optgroup label="Vidéos">
                                    <?php foreach ($project->getMedias("video") as $video): ?>
                                        <option value="<?= htmlspecialchars($video) ?>">
                                            <?= htmlspecialchars($video) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Audios">
                                    <?php foreach ($project->getMedias("audio") as $audio): ?>
                                        <option value="<?= htmlspecialchars($audio) ?>">
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

                        <div class="mb-3">
                            <label for="hintInput" class="form-label">Indice</label>
                            <textarea class="form-control" id="hintInput" name="hint" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . "/assets/partials/footer.php"; ?>
</body>

</html>
