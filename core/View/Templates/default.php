<?php

use LesMajesticiels\Regis\View\ViewHandler;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?= ViewHandler::renderPartial('head', ['title' => $title ?? '']) ?>
</head>

<body data-bs-theme="<?= $_COOKIE['bs-theme'] ?? 'light' ?>" <?= isset($autoshowVideoPlayer) ? 'autoshow-video-player' : '' ?>>
    <?= ViewHandler::renderPartial('header') ?>

    <?= $buffer ?>

    <?= ViewHandler::renderPartial('footer') ?>
</body>

</html>