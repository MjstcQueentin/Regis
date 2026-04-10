<?php

/**
 * Page de paramètres de Régis
 * Permet de tester les permissions de popup et d'autoplay des vidéos du navigateur
 */
require_once __DIR__ . '/core/autoload.php';
?>

<?php LesMajesticiels\Regis\View\ViewHandler::templateStart('default', [
    'title' => 'Paramètres',
    'autoshowVideoPlayer' => isset($_GET['testpopup']) ? true : null
]) ?>

<section class="container mb-5">
    <h2>Paramètres</h2>
</section>

<section class="container my-5">
    <h3>Permissions</h3>
    <p>
        Pour que les vidéos dans Régis fonctionnent correctement, vous devez autoriser Régis
        à ouvrir des fenêtres popup et à lancer des vidéos automatiquement avec le son.
    </p>
    <p>
        Utilisez les boutons ci-dessous pour tester les permissions de votre navigateur.
    </p>
    <div>
        <a href="?testpopup=true" class="btn btn-primary">
            Ouvrir le lecteur vidéo
        </a>
        <button class="btn btn-primary" data-trigger="video-play" data-video="/assets/test-video.mp4">
            Lancer la vidéo
        </button>
    </div>
</section>

<section class="container my-5">
    <h3>Version</h3>
    <p>
        <?= REGIS_VERSION ?>
    </p>
</section>

<script defer src="/assets/scripts/videoplayer-controls.js"></script>

<?php LesMajesticiels\Regis\View\ViewHandler::templateEnd() ?>