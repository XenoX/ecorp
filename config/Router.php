<?php

namespace App;

use App\Controller\AppController;
use App\Controller\UserController;
use Twig\Environment;

class Router
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function run(): string
    {
        $appController = new AppController($this->twig);

        if (isset($_GET['page'])) {
            if ('mail-signature' === $_GET['page']) {
                return $appController->mailSignature();
            }
            if ('upload-selfie' === $_GET['page']) {
                return $appController->uploadOne();
            }
            if ('upload-landscape' === $_GET['page']) {
                return $appController->uploadTwo();
            }
            if ('upload-afterwork' === $_GET['page']) {
                return $appController->uploadThree();
            }
            if ('contact' === $_GET['page']) {
                return $appController->contact();
            }
            if ('testimonials' === $_GET['page']) {
                return $appController->testimonials();
            }
            if ('search' === $_GET['page']) {
                return $appController->search();
            }
            if ('tools' === $_GET['page']) {
                return $appController->tools();
            }
            if ('resetData' === $_GET['page']) {
                return $appController->reset();
            }
            if ('jwt-checker' === $_GET['page']) {
                return $appController->jwt();
            }

            $userController = new UserController($this->twig);

            if ('login' === $_GET['page']) {
                return $userController->login();
            }
            if ('logout' === $_GET['page']) {
                $userController->logout();
            }
            if ('make-admin' === $_GET['page']) {
                return $userController->admin();
            }
        }

        return $appController->index();
    }
}