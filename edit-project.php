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
                "scene" => trim($_POST["scene"]),
                "title" => trim($_POST["title"]),
                "description" => trim($_POST["description"]),
                "hint" => trim($_POST["hint"]),
                "volume" => isset($_POST["volume"]) ? floatval($_POST["volume"]) : 1.0,
                "loop" => isset($_POST["loop"]) ? boolval($_POST["loop"]) : false,
            ];
            $project->addElement(new ProjectElement($elementParameters));
            break;
        case "move-element-up":
            $index = intval($_POST["index"]);
            $project->moveElementUp($index);
            break;
        case "move-element-down":
            $index = intval($_POST["index"]);
            $project->moveElementDown($index);
            break;
        case "delete-element":
            $index = intval($_POST["index"]);
            $project->deleteElement($index);
            break;
    }

    http_response_code(303);
    header("Location: /edit-project.php?action=edit&name=" . urlencode($projectName));
    exit();
}
?>


<?php LesMajesticiels\Regis\View\ViewHandler::templateStart("default", ["title" => "Modifier le projet " . $project->getTitle()]); ?>

<section class="container mb-3">
    <h2>Modifier le projet "<?= htmlspecialchars($project->getTitle()) ?>"</h2>

    <div class="d-flex flex-row flex-wrap gap-1 border rounded p-2 mt-3">
        <a href="/edit-medias.php?name=<?= urlencode($_GET["name"]) ?>" class="btn btn-primary">
            <i class="bi bi-collection-play"></i>
            <span>Ajouter ou supprimer des médias...</span>
        </a>

        <?php if (!empty($project->getMedias())): ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addElementModal">
                <i class="bi bi-plus"></i>
                <span>Ajouter un élément</span>
            </button>
        <?php endif; ?>

        <?php if (!empty($project->getElements())): ?>
            <a href="/project.php?dir=<?= urlencode($_GET["name"]) ?>" class="btn btn-success">
                <i class="bi bi-play"></i>
                <span>Jouer !</span>
            </a>
        <?php endif; ?>
    </div>
</section>

<section class="container">
    <?php if (empty($project->getElements())): ?>
        <div class="alert alert-info">Aucun élément ajouté pour le moment.</div>
    <?php else: ?>
        <?php foreach ($project->getElements() as $elementIndex => $element): ?>
            <?php $elementArray = $element->__toArray(); ?>
            <div class="d-flex flex-row gap-2 border rounded p-2 mb-2">
                <div class="d-flex flex-column justify-content-between">
                    <?php if ($elementIndex > 0): ?>
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="move-element-up">
                            <input type="hidden" name="index" value="<?= $elementIndex ?>">
                            <button type="submit" class="btn btn-link" title="Déplacer vers le haut">
                                <i class="bi bi-arrow-up"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>

                    <?php if ($elementIndex < count($project->getElements()) - 1): ?>
                        <form action="" method="POST">
                            <input type="hidden" name="action" value="move-element-down">
                            <input type="hidden" name="index" value="<?= $elementIndex ?>">
                            <button type="submit" class="btn btn-link" title="Déplacer vers le bas">
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1">
                    <p class="mt-0 mb-1 text-uppercase">
                        <span class="badge bg-primary"><?= htmlspecialchars($elementArray["scene"]) ?></span>

                        <span class="badge bg-secondary"><?= htmlspecialchars($elementArray["type"]) ?></span>

                        <?php if ($elementArray["volume"]): ?>
                            <span class="badge bg-secondary">Volume: <?= htmlspecialchars($elementArray["volume"]) ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Volume: 1.0</span>
                        <?php endif; ?>

                        <?php if ($elementArray["loop"]): ?>
                            <span class="badge bg-secondary">Loop</span>
                        <?php endif; ?>
                    </p>
                    <p class="m-0 fw-bold">
                        <?= htmlspecialchars($elementArray["title"]) ?>
                    </p>
                    <p class="m-0">
                        <?= nl2br(htmlspecialchars($elementArray["description"])) ?>
                    </p>
                    <p class="m-0 text-muted fst-italic">
                        <?= nl2br(htmlspecialchars($elementArray["hint"])) ?>
                    </p>
                </div>
                <div class="d-flex flex-row gap-1">
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

                    <div class="mb-3">
                        <label for="hintInput" class="form-label">Volume</label>
                        <input type="number" class="form-control" id="volumeInput" name="volume" min="0" max="1" step="0.01" value="1.0">
                        <small class="form-text text-muted">Niveau de volume pour les éléments audio (entre 0.0 et 1.0, par exemple "0.5" pour 50% du volume)</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="loopCheck" name="loop">
                        <label class="form-check-label" for="loopCheck">Lire en boucle</label>
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

<?php LesMajesticiels\Regis\View\ViewHandler::templateEnd(); ?>