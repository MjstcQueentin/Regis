<?php
if (empty($_GET['name'])) {
    header('Location: /index.php');
    return;
}

function project_name_to_dirname($project_name)
{
    $project_name = strtr($project_name, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    $project_name = str_replace([' ', '_', '/', '\\', '&'], ['-', '-', '-', '-', '-'], $project_name);
    return strtolower($project_name);
}


$projectDirName = project_name_to_dirname($_GET['name']);
$projectDirPath = __DIR__ . "/projects/" . $projectDirName;

switch ($_GET['action']) {
    case 'add':
        mkdir($projectDirPath);
        mkdir($projectDirPath . "/audio");
        mkdir($projectDirPath . "/video");
        file_put_contents(
            $projectDirPath . "/project.xml",
            '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><project><title>' . htmlentities($_GET['name']) . '</title><elements></elements></project>'
        );
        header('Location: /editor.php?action=edit&name=' . urlencode($projectDirName));
        return;
    case 'edit':
        if (!is_dir($projectDirPath)) {
            header('Location: /index.php');
            return;
        }

        $str = file_get_contents($projectDirPath . "/project.xml");
        $project = new SimpleXMLElement($str);
        break;
    default:
        header('Location: /index.php');
        return;
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

    <section>
        <div class="container">
            <pre><?php print_r($project) ?></pre>
        </div>
    </section>
    
    <?php include __DIR__ . '/assets/partials/footer.php'; ?>
</body>

</html>