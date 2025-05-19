<?php

/**
 * Page d'index des projets Régis
 */

$projectsDir = __DIR__ . "/projects";
$dirScan = scandir($projectsDir);

$dirScan = array_filter($dirScan, function ($item) use($projectsDir) {
    return is_dir($projectsDir . '/' . $item) && substr($item, 0, 1) != '.';
});
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Régis</title>
    <?php include __DIR__ . '/assets/partials/headlinks.php'; ?>
</head>

<body data-bs-theme="<?= $_COOKIE['bs-theme'] ?? 'light' ?>">
    <?php include __DIR__ . '/assets/partials/header.php'; ?>

    <section class="container">
        <h2>Liste des projets</h2>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nom du projet</th>
                        <th scope="col">
                            <button type="button" class="btn btn-sm btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#newProjectModal">
                                <i class="bi bi-plus-lg"></i> Nouveau projet...
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dirScan as $item): ?>
                        <tr>
                            <td>
                                <a href="project.php?dir=<?= urlencode($item) ?>">
                                    <?= htmlspecialchars($item) ?>
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-link text-decoration-none" href="project.php?dir=<?= urlencode($item) ?>" title="Ouvrir le projet">
                                    <i class="bi bi-folder2-open"></i> Ouvrir
                                </a>
                                <a class="btn btn-sm btn-link text-decoration-none" href="editor.php?action=edit&name=<?= urlencode($item) ?>" title="Ouvrir le projet">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal fade" id="newProjectModal" tabindex="-1" aria-labelledby="newProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/editor.php" method="GET">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newProjectModalLabel">Créer un nouveau projet</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">

                        <div class="mb-3">
                            <label for="projectNameFormControl" class="form-label">Nom du projet</label>
                            <input type="text" class="form-control" name="name" id="projectNameFormControl" placeholder="nouveau-projet" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">OK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/assets/partials/footer.php'; ?>
</body>

</html>