<?php

namespace LesMajesticiels\Regis\View;

use Exception;

/**
 * This class is responsible for outputing templates and partials.
 * 
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @version 1.0.0
 */
class ViewHandler
{
    private static string $template = 'default';
    private static array $templateData = [];

    /**
     * Output a template with the given data.
     * 
     * @param string $template The template to output (without the .php extension).
     * @param array $data The data to pass to the template.
     * @return void
     * @throws Exception If the template does not exist.
     */
    private static function __render(string $viewfile, array $data = []): void
    {
        if (!is_file(__DIR__ . "/{$viewfile}.php")) {
            throw new Exception("The view file {$viewfile} does not exist.");
        }

        // Extract the data to variables
        extract($data);

        // Include the view file
        include __DIR__ . "/{$viewfile}.php";
    }

    public static function renderPartial(string $partial, array $data = []): void
    {
        self::__render("Partials/{$partial}", $data);
    }

    public static function templateStart(string $template, array $data = []): void
    {
        if (!is_file(__DIR__ . "/Templates/{$template}.php")) {
            throw new Exception("The template file Templates/{$template}.php does not exist.");
        }

        if (!empty($data)) self::$templateData = $data;

        ob_start();
        self::$template = $template;
    }

    public static function templateEnd(): void
    {
        $buffer = ob_get_clean();
        $template = self::$template;

        self::__render("Templates/{$template}", array_merge(self::$templateData, ['buffer' => $buffer]));
    }
}
