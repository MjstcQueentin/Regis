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
     * @var list<ProjectElement> $elements Les éléments du projet (images, sons, vidéos, etc.)
     */
    private array $elements;

    /**
     * @var array $medias Les médias disponibles pour le projet
     */
    private array $medias;

    function __construct(string $projectDirName, ?string $projectTitle = null)
    {
        $this->dirName = $projectDirName;
        $this->title = $projectTitle ?? $projectDirName;
        $this->elements = [];
        $this->medias = [];

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

            $audios = scandir($projectPath . "/audio");
            $videos = scandir($projectPath . "/video");
            $this->medias = array_merge(
                array_map(
                    function ($audio) {
                        return "audio/" . $audio;
                    },
                    array_filter($audios, function ($item) {
                        return substr($item, 0, 1) != ".";
                    }),
                ),
                array_map(
                    function ($video) {
                        return "video/" . $video;
                    },
                    array_filter($videos, function ($item) {
                        return substr($item, 0, 1) != ".";
                    }),
                ),
            );

            if (!empty($projectXml->elements->element)) {
                foreach ($projectXml->elements->element as $elementXml) {
                    $parameters = [
                        "src" => $elementXml->attributes()->src,
                        "type" => $elementXml->attributes()->type,
                        "scene" => (string) $elementXml->scene,
                        "title" => (string) $elementXml->title,
                        "description" => (string) $elementXml->description,
                        "hint" => (string) $elementXml->hint,
                        "volume" => (float) $elementXml->attributes()->volume ?? 1.0,
                        "loop" => !empty($elementXml->attributes()->loop)
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
            $elementArray = $element->__toArray();
            $elementXml = $projectXml->elements->addChild("element");
            $elementXml->addAttribute("src", $elementArray["src"]);
            $elementXml->addAttribute("type", $elementArray["type"]);
            $elementXml->addAttribute("volume", $elementArray["volume"]);
            if ($elementArray["loop"]) {
                $elementXml->addAttribute("loop", "true");
            }
            $elementXml->addChild("scene", $elementArray["scene"]);
            $elementXml->addChild("title", $elementArray["title"]);
            $elementXml->addChild("description", $elementArray["description"]);
            $elementXml->addChild("hint", $elementArray["hint"]);
        }

        $projectXml->asXML($definitionFilePath);
    }

    /**
     * Retourne le chemin complet vers un fichier du projet.
     * @param string $src Le chemin relatif du fichier à partir du dossier du projet (ex: "audio/sound.mp3")
     * @return string Le chemin complet vers le fichier du projet sur le système de fichiers
     */
    public function getPath(string $src = ""): string
    {
        return projectDirectoryPath($this->dirName) . ($src ? "/" . $src : "");
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getModifiedTime(): int
    {
        return filemtime($this->getPath("project.xml"));
    }

    /**
     * Retourne la liste des éléments du projet.
     * @return list<ProjectElement> La liste des éléments du projet
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    public function getMedias(?string $type = null): array
    {
        if (!empty($type) && in_array($type, ["audio", "video"])) {
            return array_filter($this->medias, function ($item) use ($type) {
                return substr($item, 0, strlen($type) + 1) == $type . "/";
            });
        }

        return $this->medias;
    }

    public function addElement(ProjectElement $element): void
    {
        $this->elements[] = $element;
        $this->writeDefinitionFile();
    }
}
