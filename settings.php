<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
    <?php include __DIR__ . '/assets/partials/headlinks.php'; ?>
</head>

<body data-bs-theme="<?= $_COOKIE['bs-theme'] ?? 'light' ?>" <?= !empty($_GET['testpopup']) ? 'autoshow-video-player' : '' ?>>
    <?php include __DIR__ . '/assets/partials/header.php'; ?>
    <section>
        <div class="container">
            <h2>Paramètres</h2>
        </div>
    </section>

    <section>
        <div class="container">
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
        </div>
    </section>
    <?php include __DIR__ . '/assets/partials/footer.php'; ?>
    <script defer src="/assets/scripts/videoplayer-controls.js"></script>
</body>

</html>