<?php

/**
 * Convertit un nom de projet en nom de dossier valide
 * @param string $projectName Le nom du projet
 * @return string Le nom du dossier correspondant
 */
function projectNameToDirectoryName(string $projectName)
{
    $projectName = strtr($projectName, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    $projectName = str_replace([' ', '_', '/', '\\', '&'], ['-', '-', '-', '-', '-'], $projectName);
    return strtolower($projectName);
}