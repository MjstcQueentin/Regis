<?php

namespace LesMajesticiels\Regis;

/**
 * Représente un projet pour Regis.
 * Un projet est un ensemble d'images, sons et vidéos organisés de manière à créer une expérience interactive.
 */
class Project
{
    /**
     * @var string $dirName Le nom du dossier du projet sur le système de fichiers
     */
    private string $dirName;

    /**
     * @var string $title Le titre du projet
     */
    private string $title;

    /**
     * @var array $elements Les éléments du projet (images, sons, vidéos, etc.)
     */
    private array $elements;

    function __construct(string $projectDirName, ?string $projectTitle = null)
    {
        $this->dirName = $projectDirName;
        $this->title = $projectTitle ?? $projectDirName;
        $this->elements = [];

        $projectPath = projectDirectoryPath($this->dirName);

        if (!is_dir($projectPath)) {
            // Créer le projet qui n'existe pas encore sur le système de fichiers
            mkdir($projectPath);
            mkdir($projectPath . "/audio");
            mkdir($projectPath . "/video");
            file_put_contents($projectPath . "/project.xml", '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><project><title>' . htmlentities($projectTitle) . "</title><elements></elements></project>");
        } else {
            // Charger les éléments du projet depuis le fichier XML
            $str = file_get_contents($projectPath . "/project.xml");
            $projectXml = new \SimpleXMLElement($str);

            if (!empty($projectXml->elements->element)) {
                foreach ($projectXml->elements->element as $elementXml) {
                    $parameters = [
                        "src" => (string) $elementXml["src"],
                        "type" => (string) $elementXml["type"],
                        "scene" => (string) $elementXml["scene"],
                        "title" => (string) $elementXml["title"],
                        "description" => (string) $elementXml["description"],
                        "hint" => (string) $elementXml["hint"],
                    ];
                    $this->elements[] = new ProjectElement($parameters);
                }
            }
        }
    }

    /**
     * Enregistre les éléments du projet dans le fichier XML de définition du projet.
     * @return void
     */
    private function writeDefinitionFile(): void
    {
        $definitionFilePath = projectDirectoryPath($this->dirName) . "/project.xml";
        $projectXml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><project><title>' . htmlentities($this->title) . "</title><elements></elements></project>");

        foreach ($this->elements as $element) {
            $elementXml = $projectXml->elements->addChild("element");
            $elementXml->addAttribute("src", $element->src);
            $elementXml->addAttribute("type", $element->type);
            $elementXml->addChild("scene", $element->scene);
            $elementXml->addChild("title", $element->title);
            $elementXml->addChild("description", $element->description);
            $elementXml->addChild("hint", $element->hint);
        }

        $projectXml->asXML($definitionFilePath);
    }
}
