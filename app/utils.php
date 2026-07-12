<?php

/**
 * Checks if a project name is valid, otherwise redirects
 * the user to the projects list page.
 * @param string $projectName The project name to validate
 * @return void
 */
function requireValidProjectName($projectName)
{
    if (empty($projectName)) {
        http_response_code(303);
        header('Location: /index.php');
        exit;
    }

    $projectDirectoryName = projectNameToDirectoryName($projectName);
    $projectDir = REGIS_PROJECTS . $projectDirectoryName;

    if (!is_dir($projectDir)) {
        http_response_code(303);
        header('Location: /index.php');
        exit;
    }
}

/**
 * Convertit un nom de projet en nom de dossier valide
 * @param string $projectName Le nom du projet
 * @return string Le nom du dossier correspondant
 */
function projectNameToDirectoryName(string $projectName): string
{
    if (empty($projectName)) throw new InvalidArgumentException("Le nom du projet ne peut pas être vide.");

    $projectName = strtr($projectName, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    $projectName = str_replace([' ', '_', '/', '\\', '&'], ['-', '-', '-', '-', '-'], $projectName);
    return strtolower($projectName);
}

/**
 * Retourne le chemin absolu du dossier d'un projet
 * @param string $projectName Le nom du projet
 * @return string Le chemin absolu du dossier du projet
 */
function projectDirectoryPath(string $projectName): string
{
    if (empty($projectName)) throw new InvalidArgumentException("Le nom du projet ne peut pas être vide.");

    $projectDirectoryName = projectNameToDirectoryName($projectName);
    $rawPath = REGIS_PROJECTS . $projectDirectoryName;

    return is_dir($rawPath) ? realpath($rawPath) : $rawPath;
}
