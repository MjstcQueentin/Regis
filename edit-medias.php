<?php

/**
 * edit-medias.php
 * ---
 * Permet d'ajouter ou de supprimer les médias d'un projet Régis.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */

require_once __DIR__ . '/core/utils.php';

$projectName = $_GET['name'];
if (empty($projectName)) {
    http_response_code(303);
    header('Location: /index.php');
    exit;
}
$projectDir = __DIR__ . '/projects/' . projectNameToDirectoryName($projectName);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "Aucune action réalisée.";
    $action = $_POST['action'];

    if ($action == "delete") {
        $filename = $_POST['filename'];
        $type = $_POST['type'];
        $filePath = $projectDir . '/' . $type . '/' . $filename;

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
        $file = $_FILES['file'];

        if ($file['error'] == 0) {
            $filename = basename($file['name']);
            $filetype = mime_content_type($file['tmp_name']);
            $type = substr($filetype, 0, stripos($filetype, '/')); // 'video' ou 'audio'
            $targetPath = $projectDir . '/' . $type . '/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $message = "Le fichier a été uploadé avec succès.";
            } else {
                $message = "Une erreur est survenue lors de l'upload du fichier.";
            }
        } else {
            $message = "Code d'erreur : ".$file['error']; // Code d'erreur de l'upload
        }
    } else {
        $message = "Action inconnue.";
    }
}

$videos = scandir($projectDir . '/video');
$audios = scandir($projectDir . '/audio');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les médias du projet "<?= htmlspecialchars($projectName) ?>"</title>
    <?php include __DIR__ . '/assets/partials/headlinks.php'; ?>
</head>

<body data-bs-theme="<?= $_COOKIE['bs-theme'] ?? 'light' ?>">
    <?php include __DIR__ . '/assets/partials/header.php'; ?>

    <section class="container">
        <h1>Médias du projet "<?= htmlspecialchars($projectName) ?>"</h1>
    </section>

    <?php if (!empty($message)): ?>
        <section class="container mb-3">
            <div class="alert alert-info" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="container">
        <div class="row gap-1">
            <div class="table-responsive border rounded col p-0">
                <table class="table">
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
                                        <input type="hidden" name="type" value="video">
                                        <input type="hidden" name="filename" value="<?= htmlspecialchars($video) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer le média">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive border rounded col p-0">
                <table class="table">
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
                                        <input type="hidden" name="type" value="audio">
                                        <input type="hidden" name="filename" value="<?= htmlspecialchars($audio) ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer le média">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="container mt-5">
        <div class="border rounded p-4">
            <h2>Ajouter un média</h2>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload">

                <div class="mb-3">
                    <label for="mediaFile" class="form-label">Fichier média</label>
                    <input class="form-control" type="file" id="mediaFile" name="file" accept="video/*,audio/*" required>
                </div>

                <button type="submit" class="btn btn-primary">Ajouter le média</button>
            </form>
        </div>
    </section>

    <?php include __DIR__ . '/assets/partials/footer.php'; ?>
</body>

</html>