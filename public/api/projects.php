<?php

/**
 * Project index
 * This API endpoint returns the list of all projects available on this Regis server
 *
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @version 2.0.0
 * @license MIT
 */

use LesMajesticiels\Regis\Projects\Project;

require_once __DIR__ . "/../../app/autoload.php";

$dirScan = scandir(REGIS_PROJECTS);
$dirScan = array_filter($dirScan, function ($item) {
    return is_dir(REGIS_PROJECTS . "/" . $item) && substr($item, 0, 1) != ".";
});

$projects = [];

foreach ($dirScan as $dir) {
    $project = new Project($dir);

    $projects[] = [
        "dir" => $dir,
        "title" => $project->getTitle(),
    ];
}

header("Content-Type: application/json");
echo json_encode($projects);
