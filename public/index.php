<?php

session_start();

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use App\Router;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

Debug::enable();

$loader = new FilesystemLoader(__DIR__.'/../templates');
$twig = new Environment($loader, [
    'debug' => true,
]);
$twig->addExtension(new DebugExtension());

$app = [
    'query' => $_GET,
    'params' => $_POST,
    'session' => $_SESSION,
    'server' => $_SERVER,
    'request' => $_REQUEST,
];

$twig->addGlobal('app', $app);

echo (new Router($twig))->run();