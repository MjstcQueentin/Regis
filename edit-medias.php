<?php

/**
 * edit-medias.php
 * ---
 * Permet d'ajouter ou de supprimer les médias d'un projet Régis.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */
require_once __DIR__ . "/core/autoload.php";

use LesMajesticiels\Regis\Project;
use LesMajesticiels\Regis\ProjectElement;

$projectName = $_GET['name'];
$project = new Project($projectName);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "Aucune action réalisée.";
    $action = $_POST['action'];

    if ($action == "delete") {
        // Supprimer le média spécifié
        $path = $_POST['path'];
        $filePath = $project->getPath($path);

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                $message = "Le fichier a été supprimé avec succès.";
            } else {
                $message = "Une erreur est survenue lors de la suppression du fichier.";
            }
        } else {
            $message = "Le fichier spécifié n'existe pas.";
        }
    } elseif ($action == "upload") {
        // Ajouter un nouveau média
        $file = $_FILES['file'];

        if ($file['error'] == 0) {
            $filename = basename($file['name']);
            $filetype = mime_content_type($file['tmp_name']);
            $type = substr($filetype, 0, stripos($filetype, '/')); // 'video' ou 'audio'
            $targetPath = $project->getPath($type . '/' . $filename);

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $message = "Le fichier a été uploadé avec succès.";
            } else {
                $message = "Une erreur est survenue lors de l'upload du fichier.";
            }
        } else {
            $message = "Code d'erreur : " . $file['error']; // Code d'erreur de l'upload
        }
    } else {
        $message = "Action inconnue.";
    }

    $project->refreshMediaList();
}

$videos = $project->getMedias('video');
$audios = $project->getMedias('audio');

?>


<?php LesMajesticiels\Regis\View\ViewHandler::templateStart('default', ['title' => 'Gérer les médias du projet ' . $projectName]) ?>

<section class="container mb-3">
    <h2>Gérer les médias du projet "<?= htmlspecialchars($projectName) ?>"</h2>

    <div class="d-flex flex-row flex-wrap gap-1 border rounded p-2 mt-3">
        <a href="/edit-project.php?name=<?= urlencode($_GET["name"]) ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i>
            <span>Composer le projet...</span>
        </a>

        <?php if (!empty($project->getElements())): ?>
            <a href="/project.php?dir=<?= urlencode($_GET["name"]) ?>" class="btn btn-success">
                <i class="bi bi-play"></i>
                <span>Jouer !</span>
            </a>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($message)): ?>
    <section class="container mt-3">
        <div class="alert alert-info" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    </section>
<?php endif; ?>

<section class="container mt-3">
    <div class="accordion" id="addMediaAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Envoyer un média sur le serveur
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#addMediaAccordion">
                <div class="accordion-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload">

                        <div class="mb-3">
                            <label for="mediaFile" class="form-label">Fichier média</label>
                            <input class="form-control" type="file" id="mediaFile" name="file" accept="video/*,audio/*" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Ajouter le média</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container mt-3">
    <div class="row gap-1 mx-0">
        <div class="table-responsive border rounded col p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th scope="col">Vidéo</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($videos as $video) : ?>
                        <?php if (substr($video, 0, 1) === '.') continue; ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($video) ?></th>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="path" value="<?= htmlspecialchars($video) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer le média">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($videos)) : ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted">Aucune vidéo ajoutée pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-responsive border rounded col p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th scope="col">Audio</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($audios as $audio) : ?>
                        <?php if (substr($audio, 0, 1) === '.') continue; ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($audio) ?></th>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="path" value="<?= htmlspecialchars($audio) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer le média">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($audios)) : ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted">Aucun audio ajouté pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php LesMajesticiels\Regis\View\ViewHandler::templateEnd() ?>