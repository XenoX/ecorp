<?php

namespace App\Controller;

use JetBrains\PhpStorm\NoReturn;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    protected function redirect(string $pageName, int $statusCode = 302)
    {
        header('Location: ?page='. $pageName, true, $statusCode);
        exit();
    }

    protected function render(string $path, array $params = []): string
    {
        try {
            return $this->twig->render($path, $params);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return $e->getMessage();
        }
    }
}