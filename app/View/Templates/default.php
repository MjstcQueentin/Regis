<?php

use LesMajesticiels\Regis\View\ViewHandler;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php ViewHandler::renderPartial('head', ['title' => $title ?? '']); ?>
</head>

<body data-bs-theme="<?= $_COOKIE['bs-theme'] ?? 'light' ?>" <?= !empty($autoshowVideoPlayer) ? 'autoshow-video-player' : '' ?>>
    <?php ViewHandler::renderPartial('header'); ?>

    <?= !empty($buffer) ? $buffer : "" ?>

    <?php ViewHandler::renderPartial('footer'); ?>
</body>

</html>
