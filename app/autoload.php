<?php
/**
 * REGIS
 * Your theater companion for sound and video on stage
 *
 * @author Quentin Pugeat <contact@quentinpugeat.fr>
 * @author Drama-Tea'c Circus <dramateaccircus@proton.me>
 * @version 2.0.0
 */

require_once __DIR__ . "/utils.php";
require_once __DIR__ . "/Projects/Project.php";
require_once __DIR__ . "/Projects/ProjectElement.php";
require_once __DIR__ . "/View/ViewHandler.php";

define('REGIS_CORE', __DIR__ . '/');
define('REGIS_FRONT', __DIR__ . '/../public/');
define('REGIS_PROJECTS', __DIR__ . '/../public/projects/');
define('REGIS_VERSION', '2.0.0');
