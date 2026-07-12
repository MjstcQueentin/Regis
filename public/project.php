<?php

/**
 * Page de visionnage de projet
 * --
 * Permet de visionner et d'exécuter les éléments d'un projet Régis.
 * Les éléments sont exécutés à l'aide d'un lecteur vidéo/audio intégré.
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @license MIT
 */
require_once __DIR__ . '/../app/autoload.php';

$dir = $_GET['dir'];
$project = new LesMajesticiels\Regis\Projects\Project($dir);

// header('Last-Modified: ' . date('D, d M Y H:i:s', $project->getModifiedTime()) . ' GMT');
?>

<?php LesMajesticiels\Regis\View\ViewHandler::templateStart('default', [
    'title' => $project->getTitle(),
    'autoshowVideoPlayer' => $project->hasVideos(),
    'prefetch' => array_map(function ($element) use ($project) {
        return $project->getPath($element->__toArray()['src']);
    }, $project->getElements())
]) ?>

<section class="container mb-3">
    <h2><?= htmlspecialchars($project->getTitle()) ?></h2>

    <div class="d-flex flex-row flex-wrap gap-1 border rounded p-2 mt-3">
        <?php if($project->hasVideos()): ?>
            <button class="btn btn-primary" data-trigger="video-play" data-video="/assets/test-video.mp4">
                <i class="bi bi-play"></i> Vidéo de test
            </button>

            <button class="btn btn-secondary" data-trigger="video-stop">
                <i class="bi bi-stop"></i> Arrêter la vidéo de test
            </button>
        <?php endif; ?>

        <button class="btn btn-danger" data-trigger="video-stop reset-all">
            <i class="bi bi-stop"></i> Remise à zéro
        </button>

        <a href="/edit-medias.php?dir=<?= urlencode($_GET["dir"]) ?>" class="btn btn-outline-secondary">
            <i class="bi bi-collection-play"></i>
            <span>Médias...</span>
        </a>

        <a href="/edit-project.php?dir=<?= urlencode($_GET["dir"]) ?>" class="btn btn-outline-secondary">
            <i class="bi bi-pencil"></i>
            <span>Composition...</span>
        </a>
    </div>
</section>

<section>
    <div class="mx-5">
        <?php foreach ($project->getElements() as $elementObject): ?>
            <?php $element = $elementObject->__toArray(); ?>

            <div class="row border rounded py-2 mb-2">
                <div class="col-sm-1">
                    <span class="badge text-uppercase text-bg-primary"><?= htmlspecialchars($element['scene']) ?></span>
                </div>
                <div class="col-sm-7">
                    <h5 class="fw-bold fs-6 mb-1"><?= htmlspecialchars($element['title']) ?></h5>
                    <p class="small m-0"><?= nl2br(htmlspecialchars(trim($element['description']))) ?></p>
                    <?php if (!empty($element['hint'])) : ?>
                        <p class="small text-muted m-0"><?= nl2br(htmlspecialchars(trim($element['hint']))) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-sm-4">
                    <?php if (!is_file($project->getPath($element['src']))): ?>
                        <div class="alert alert-danger m-0 py-2">
                            Fichier invalide !
                        </div>
                    <?php elseif (substr($element['type'], 0, stripos($element['type'], '/')) == "audio"): ?>
                        <audio controls class="w-100" preload="auto" <?= $element['loop'] ? 'loop' : '' ?>
                            <?= !empty($element['volume']) ? 'volume="' . $element['volume'] . '"' : '' ?>
                            src="/projects/<?= htmlspecialchars($dir) ?>/<?= htmlspecialchars($element['src']) ?>"></audio>
                    <?php else: ?>
                        <div class="d-flex flex-row gap-1 align-items-center">
                            <button class="btn btn-sm btn-primary" data-trigger="video-play"
                                data-video="/projects/<?= htmlspecialchars($dir) ?>/<?= htmlspecialchars($element['src']) ?>"
                                <?= !empty($element['loop']) ? 'data-loop="true"' : '' ?>>
                                <i class="bi bi-play-fill"></i> Lancer
                            </button>
                            <button class="btn btn-sm btn-secondary" data-trigger="video-stop">
                                <i class="bi bi-stop-fill"></i> Arrêter
                            </button>
                        </div>
                    <?php endif; ?>

                    <span class="badge text-uppercase text-bg-secondary"><?= volumeIcon($element['volume'] || 1) ?> <?= (($element['volume'] || 1) * 100) ?> &percnt;</span>
                    <?php if($element['loop']): ?>
                        <span class="badge text-uppercase text-bg-info">
                            <i class="bi bi-arrow-repeat"></i> En boucle
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
    const audios = document.querySelectorAll('audio[volume]');
    audios.forEach(audio => {
        audio.volume = audio.getAttribute('volume');
    });
</script>
<script defer src="/assets/scripts/projectplayer.js"></script>
<script defer src="/assets/scripts/videoplayer-controls.js"></script>

<?php LesMajesticiels\Regis\View\ViewHandler::templateEnd() ?>
