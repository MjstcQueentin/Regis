<?php

/**
 * Page de visionnage de projet
 */

$dir = $_GET['dir'];
$path = __DIR__ . '/projects/' . $dir . '/project.xml';
$str = file_get_contents($path);
$project = new SimpleXMLElement($str);

header('Last-Modified: ' . date('D, d M Y H:i:s', filemtime($path)) . ' GMT');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($project->title) ?> | Régis</title>
    <?php include __DIR__ . '/assets/partials/headlinks.php'; ?>
</head>

<body data-bs-theme="<?= $_COOKIE['bs-theme'] ?? 'light' ?>" autoshow-video-player>
    <?php include __DIR__ . '/assets/partials/header.php'; ?>

    <section>
        <div class="container d-flex flex-row justify-content-between align-items-center">
            <h2>
                <?= htmlspecialchars($project->title) ?>
            </h2>
            <div>
                <div class="d-flex flex-row gap-1 align-items-baseline justify-content-end mb-1">
                    <button class="btn btn-sm btn-outline-primary" data-trigger="video-play" data-video="/assets/test-video.mp4">
                        <i class="bi bi-play"></i> Vidéo de test
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" data-trigger="video-stop">
                        <i class="bi bi-stop"></i>
                    </button>
                </div>
                <div class="d-flex flex-row gap-1 align-items-baseline justify-content-end">
                    <button class="btn btn-sm btn-outline-danger" data-trigger="video-stop reset-all">
                        <i class="bi bi-stop"></i> Remise à zéro
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="table-responsive container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Scene</th>
                        <th scope="col">Title and cues</th>
                        <th scope="col">Player</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($project->elements->element as $element): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($element->scene) ?>
                            </td>
                            <td>
                                <b><?= htmlspecialchars($element->title) ?></b><br>
                                <i><?= htmlspecialchars($element->description) ?></i>
                            </td>
                            <td>
                                <?php if (!is_file(__DIR__ . '/projects/' . $dir . '/' . $element->attributes()['src'])): ?>
                                    <div class="alert alert-danger m-0 py-2">
                                        Fichier invalide !
                                    </div>
                                <?php elseif (substr($element->attributes()['type'], 0, stripos($element->attributes()['type'], '/')) == "audio"): ?>
                                    <audio controls class="w-100" preload="none" <?= !empty($element->attributes()['loop']) ? 'loop' : '' ?>
                                        <?= !empty($element->attributes()['volume']) ? 'volume="' . $element->attributes()['volume'] . '"' : '' ?>
                                        src="/projects/<?= htmlspecialchars($dir) ?>/<?= htmlspecialchars($element->attributes()['src']) ?>"></audio>
                                <?php else: ?>
                                    <div class="d-flex flex-row gap-2 align-items-center">
                                        <button class="btn btn-primary" data-trigger="video-play"
                                            data-video="/projects/<?= htmlspecialchars($dir) ?>/<?= htmlspecialchars($element->attributes()['src']) ?>"
                                            <?= !empty($element->attributes()['loop']) ? 'data-loop="true"' : '' ?>>
                                            <i class="bi bi-play-fill"></i> Lancer
                                        </button>
                                        <button class="btn btn-secondary" data-trigger="video-stop">
                                            <i class="bi bi-stop-fill"></i> Arrêter
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($element->hint)) : ?>
                                    <br><i><?= htmlspecialchars($element->hint) ?></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include __DIR__ . '/assets/partials/footer.php'; ?>
    <script>
        const audios = document.querySelectorAll('audio[volume]');
        audios.forEach(audio => {
            audio.volume = audio.getAttribute('volume');
        });
    </script>
    <script defer src="/assets/scripts/projectplayer.js"></script>
    <script defer src="/assets/scripts/videoplayer-controls.js"></script>
</body>

</html>