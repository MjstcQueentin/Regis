<?php

/**
 * edit-project.php
 * ---
 * Permet de changer l'ordre d'apparition des différents médias dans le projet.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */

require_once __DIR__ . '/core/utils.php';

$projectName = $_GET['name'];
$projectDirName = projectNameToDirectoryName($projectName);
$projectDirPath = projectDirectoryPath($projectName);

switch ($_GET['action']) {
    case 'add':
        mkdir($projectDirPath);
        mkdir($projectDirPath . "/audio");
        mkdir($projectDirPath . "/video");
        file_put_contents(
            $projectDirPath . "/project.xml",
            '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><project><title>' . htmlentities($projectName) . '</title><elements></elements></project>'
        );
        header('Location: /edit-project.php?action=edit&name=' . urlencode($projectDirName));
        return;
    case 'edit':
        requireValidProjectName($projectName);

        $str = file_get_contents($projectDirPath . "/project.xml");
        $project = new SimpleXMLElement($str);
        break;
    default:
        http_response_code(303);
        header('Location: /index.php');
        exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier <?= htmlspecialchars($project->title) ?> | Régis</title>
    <?php include __DIR__ . '/assets/partials/headlinks.php'; ?>
</head>

<body data-bs-theme="<?= $_COOKIE['bs-theme'] ?? 'light' ?>">
    <?php include __DIR__ . '/assets/partials/header.php'; ?>

    <section class="container">
        <h2>Modifier le projet "<?= htmlspecialchars($project->title) ?>"</h2>
        <a href="/edit-medias.php?name=<?= urlencode($_GET['name']) ?>" class="btn btn-primary">Modifier les médias du projet</a>
    </section>

    <section>
        <div class="container mt-3">
            <pre><?php print_r($project) ?></pre>
        </div>
    </section>

    <?php include __DIR__ . '/assets/partials/footer.php'; ?>
</body>

</html>