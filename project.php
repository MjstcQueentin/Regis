<?php

/**
 * Page de visionnage de projet
 * --
 * Permet de visionner et d'exécuter les éléments d'un projet Régis.
 * Les éléments sont exécutés à l'aide d'un lecteur vidéo/audio intégré.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */
require_once __DIR__ . '/core/autoload.php';

$dir = $_GET['dir'];
$project = new LesMajesticiels\Regis\Project($dir);

header('Last-Modified: ' . date('D, d M Y H:i:s', $project->getModifiedTime()) . ' GMT');
?>

<?php LesMajesticiels\Regis\View\ViewHandler::templateStart('default', [
    'title' => $project->getTitle(),
    'autoshowVideoPlayer' => true,
    'prefetch' => array_map(function ($element) use ($project) {
        return $project->getPath($element->__toArray()['src']);
    }, $project->getElements())
]) ?>

<section>
    <div class="container d-flex flex-row justify-content-between align-items-center">
        <h2>
            <?= htmlspecialchars($project->getTitle()) ?>
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
                <?php foreach ($project->getElements() as $elementObject): ?>
                    <?php $element = $elementObject->__toArray(); ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($element['scene']) ?>
                        </td>
                        <td>
                            <b><?= htmlspecialchars($element['title']) ?></b><br>
                            <i><?= htmlspecialchars($element['description']) ?></i>
                        </td>
                        <td>
                            <?php if (!is_file($project->getPath($element['src']))): ?>
                                <div class="alert alert-danger m-0 py-2">
                                    Fichier invalide !
                                </div>
                            <?php elseif (substr($element['type'], 0, stripos($element['type'], '/')) == "audio"): ?>
                                <audio controls class="w-100" preload="auto" <?= $element['loop'] ? 'loop' : '' ?>
                                    <?= !empty($element['volume']) ? 'volume="' . $element['volume'] . '"' : '' ?>
                                    src="/projects/<?= htmlspecialchars($dir) ?>/<?= htmlspecialchars($element['src']) ?>"></audio>
                            <?php else: ?>
                                <div class="d-flex flex-row gap-2 align-items-center">
                                    <button class="btn btn-primary" data-trigger="video-play"
                                        data-video="/projects/<?= htmlspecialchars($dir) ?>/<?= htmlspecialchars($element['src']) ?>"
                                        <?= !empty($element['loop']) ? 'data-loop="true"' : '' ?>>
                                        <i class="bi bi-play-fill"></i> Lancer
                                    </button>
                                    <button class="btn btn-secondary" data-trigger="video-stop">
                                        <i class="bi bi-stop-fill"></i> Arrêter
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($element['hint'])) : ?>
                                <br><i><?= htmlspecialchars($element['hint']) ?></i>
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

<?php LesMajesticiels\Regis\View\ViewHandler::templateEnd() ?>