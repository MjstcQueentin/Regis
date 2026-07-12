<?php

/**
 * edit-project.php
 * ---
 * Permet de changer l'ordre d'apparition des différents médias dans le projet.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */
require_once __DIR__ . "/../app/autoload.php";

use LesMajesticiels\Regis\Projects\Project;
use LesMajesticiels\Regis\Projects\ProjectElement;

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
                "hint" => trim($_POST["hint"] ?? ""),
                "volume" => isset($_POST["volume"]) ? floatval($_POST["volume"]) : 1.0,
                "loop" => isset($_POST["loop"]) ? boolval($_POST["loop"]) : false,
            ];
            $project->addElement(
                new ProjectElement($elementParameters),
                isset($_POST["index"]) ? intval($_POST["index"]) : null
            );
            break;
        case "edit-element":
            $elementIndex = intval($_POST["index"]);
            $elementParameters = [
                "src" => $_POST["src"],
                "type" => mime_content_type($project->getPath($_POST["src"])),
                "scene" => trim($_POST["scene"]),
                "title" => trim($_POST["title"]),
                "description" => trim($_POST["description"]),
                "hint" => trim($_POST["hint"] ?? ""),
                "volume" => isset($_POST["volume"]) ? floatval($_POST["volume"]) : 1.0,
                "loop" => isset($_POST["loop"]) ? boolval($_POST["loop"]) : false,
            ];
            $project->replaceElement($elementIndex, new ProjectElement($elementParameters));
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
            <div class="d-flex flex-row gap-2 border rounded p-2 mb-2" data-element-src="<?= $elementArray["src"] ?>" data-element-index="<?= $elementIndex ?>">
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

                        <?php if (!empty($elementArray["volume"])): ?>
                            <?php $volumeClass = $elementArray["volume"] < 0.5 ? "bi-volume-down" : "bi-volume-up"; ?>
                            <span class="badge bg-secondary" data-volume="<?= $elementArray["volume"] ?>" title="Niveau du volume"><i class="bi <?= $volumeClass ?>"></i> <?= number_format($elementArray["volume"] * 100, 0) ?>%</span>
                        <?php else: ?>
                            <span class="badge bg-secondary" data-volume="1" title="Niveau du volume"><i class="bi bi-volume-up"></i> 100%</span>
                        <?php endif; ?>

                        <?php if ($elementArray["loop"]): ?>
                            <span class="badge bg-info" data-loop="true"><i class="bi bi-arrow-repeat"></i> en boucle</span>
                        <?php endif; ?>
                    </p>
                    <p class="m-0 fw-bold">
                        <?= htmlspecialchars(trim($elementArray["title"])) ?>
                    </p>
                    <p class="m-0">
                        <?= nl2br(htmlspecialchars(trim($elementArray["description"]))) ?>
                    </p>
                    <p class="m-0 text-muted fst-italic">
                        <?= nl2br(htmlspecialchars(trim($elementArray["hint"]))) ?>
                    </p>
                </div>
                <div class="d-flex flex-row align-items-start gap-1">
                    <button type="button" class="btn btn-secondary" data-trigger="element-editor" data-index="<?= $elementIndex ?>" title="Modifier cet élément">
                        <i class="bi bi-pencil"></i>
                    </button>

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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addElementModalLabel">Ajouter un élément</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" class="needs-validation">
                <input type="hidden" name="action" value="add-element">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-7">
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
                                <input type="text" class="form-control" id="sceneInput" name="scene" placeholder="Par exemple, Scene 1" required>
                            </div>

                            <div class="mb-3">
                                <label for="titleInput" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="titleInput" name="title" placeholder="Par exemple, Musique Jazz des années 20" required>
                            </div>

                            <div class="mb-3">
                                <label for="descriptionInput" class="form-label">Description</label>
                                <textarea class="form-control" id="descriptionInput" name="description" aria-describedby="descriptionHelpBlock" rows="6" required></textarea>
                                <div id="descriptionHelpBlock" class="form-text">
                                    Généralement, on copie-colle du script le moment de la scène où le son est censé être joué.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="mb-3">
                                <label for="hintInput" class="form-label">Indice</label>
                                <textarea class="form-control" id="hintInput" name="hint" aria-describedby="hintHelpBlock" rows="4"></textarea>
                                <div id="hintHelpBlock" class="form-text">
                                    Si nécessaire, ajouter des indications sur la manière de jouer ou de terminer cet élément.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="volumeInput" class="form-label">Volume</label>
                                <input type="number" class="form-control" id="volumeInput" name="volume" min="0" max="1" step="0.01" value="1.0">
                                <small class="form-text text-muted">Entre 0.0 et 1.0, par exemple "0.5" pour 50% du volume</small>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="loopCheck" name="loop">
                                <label class="form-check-label" for="loopCheck">Lire en boucle</label>
                            </div>

                            <div class="mb-3">
                                <label for="indexSelect" class="form-label">Insérer avant</label>
                                <select class="form-select" id="indexSelect" name="index">
                                    <?php foreach ($project->getElements() as $index => $element): ?>
                                        <option value="<?= htmlspecialchars($index) ?>">
                                            <?= htmlspecialchars($element->__toArray()["scene"]) ?>: <?= htmlspecialchars($element->__toArray()["title"]) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="<?= htmlspecialchars(count($project->getElements())) ?>" selected>
                                        Insérer en dernier
                                    </option>
                                </select>
                            </div>
                        </div>
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
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const addElementModal = new bootstrap.Modal(document.getElementById("addElementModal"));

        document.querySelectorAll("[data-trigger='element-editor']").forEach(button => {
            button.addEventListener("click", () => {
                const elementIndex = button.getAttribute("data-index");
                document.querySelector("#addElementModal input[name='action']").value = "edit-element";
                document.querySelector("#addElementModal select[name='index']").value = elementIndex;

                const element = document.querySelector(`[data-element-index='${elementIndex}']`);
                const src = element.getAttribute("data-element-src");
                const scene = element.querySelector("p.text-uppercase .badge").textContent.trim();
                const title = element.querySelector("p.fw-bold").textContent.trim();
                const description = element.querySelector("p:not(.text-uppercase):not(.fw-bold):not(.text-muted)").textContent.trim();
                const hint = element.querySelector("p.text-muted").textContent.trim();
                const volume = element.querySelector("[data-volume]").getAttribute("data-volume");
                const loop = element.querySelectorAll("[data-loop]").length > 0;

                document.querySelector("#addElementModal select[name='src']").value = src;
                document.querySelector("#addElementModal input[name='scene']").value = scene;
                document.querySelector("#addElementModal input[name='title']").value = title;
                document.querySelector("#addElementModal textarea[name='description']").value = description;
                document.querySelector("#addElementModal textarea[name='hint']").value = hint;
                document.querySelector("#addElementModal input[name='volume']").value = volume;
                document.querySelector("#addElementModal input[name='loop']").checked = loop;

                document.querySelector("#addElementModal #indexSelect").parentElement.style.display = "none";

                addElementModal.show();
            });
        });

        document.getElementById("addElementModal").addEventListener("hidden.bs.modal", () => {
            document.querySelector("#addElementModal form").reset();
            document.querySelector("#addElementModal input[name='action']").value = "add-element";
            document.querySelector("#addElementModal select[name='index']").value = "";
            document.querySelector("#addElementModal #indexSelect").parentElement.style.display = "";
        });
    });
</script>

<script src="https://assets.lesmajesticiels.org/libraries/bootstrap/bootstrap-5.x-custom/form-validation.js"></script>

<?php LesMajesticiels\Regis\View\ViewHandler::templateEnd(); ?>
