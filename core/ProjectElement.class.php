<?php

namespace LesMajesticiels\Regis;

/**
 * Représente un élément d'un projet pour Regis.
 * Un élément peut être une image, un son, une vidéo ou tout autre média utilisé dans le projet.
 */
class ProjectElement
{
    /**
     * @var string $src Chemin relatif vers le fichier média (par exemple, "video/mon_video.mp4", "audio/mon_audio.mp3", etc.)
     */
    private string $src;

    /**
     * @var string|null $type Type MIME du média (par exemple, "video/mp4", "audio/mpeg", etc.)
     */
    private string $type;

    /**
     * @var string $scene Le numéro de la scène à laquelle cet élément est associé
     */
    private string $scene;

    /**
     * @var string $title Le titre de l'élément
     */
    private string $title;

    /**
     * @var string $description La description de l'élément
     */
    private string $description;

    /**
     * @var string $hint L'indice associé à l'élément
     */
    private string $hint;

    /**
     * Constructeur de la classe ProjectElement.
     * @param array $parameters Un tableau associatif contenant les paramètres de l'élément (src, type, scene, title, description, hint)
     */
    function __construct(array $parameters = [])
    {
        $this->src = $parameters["src"] ?? "";
        $this->type = $parameters["type"] ?? "";
        $this->scene = $parameters["scene"] ?? "";
        $this->title = $parameters["title"] ?? "";
        $this->description = $parameters["description"] ?? "";
        $this->hint = $parameters["hint"] ?? "";
    }
}
