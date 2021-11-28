<?php

namespace App\Controller;

use App\DAO\TestimonialManager;
use App\DAO\UserManager;
use App\Model\Testimonial;
use Twig\Environment;

class UserController extends AbstractController
{
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
    }

    public function admin(): void
    {
        if (!isset($_GET['to']) || 'bob' !== $_GET['to']) {
            $this->redirect('');
        }

        if ('john' !== $_SESSION['username']) {
            $this->redirect('');
        }

        $testimonial = new Testimonial();
        $testimonial
            ->setRole('Well Played')
            ->setName('FLAG')
            ->setMessage('LV_CSRF_IS_NIC3')
        ;

        $testimonialManager = new TestimonialManager();
        $testimonialManager->create($testimonial);

        $this->redirect('testimonials');
    }

    public function login(): string
    {
        if (isset($_SESSION['username'])) {
            $this->redirect('');
        }

        if (!empty($_POST)) {
            if (empty($_POST['username'])|| empty($_POST['password'])) {
                $this->redirect('login');
            }

            if ('john' === $_POST['username'] && 'tiger12' === $_POST['password']) {
                $_SESSION['username'] = 'john';
                $_SESSION['role'] = 'admin';

                setcookie('flag', 'LV_XSS_ST0RED_YOU_ROCK');

                $this->redirect('');
            }

            if ('bill' === $_POST['username'] && 'yoh' === $_POST['password']) {
                $_SESSION['username'] = 'bill';
                $_SESSION['role'] = 'yoh';

                setcookie('flag', 'LV_BRUTE_FORCE_ITS_4_LAST_CHANCE');

                $this->redirect('');
            }

            $userManager = new UserManager();
            if ($userManager->login($_POST['username'], $_POST['password'])) {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['role'] = 'user';

                setcookie('flag', 'LV_SQL_INJECTION_4_THE_WIN');

                if ('xenox' === strtolower($_POST['username'])) {
                    setcookie('flag', 'LV_SQL_INJECTION_WITH_AN_ADMIN_ACCOUNT');
                    $_SESSION['role'] = 'admin';
                }

                $this->redirect('');
            }

            $error = true;
        }

        return $this->render('User/login.html.twig', ['error' => $error ?? false]);
    }

    public function logout(): void
    {
        session_destroy();
        setcookie('flag', '', time() - 3600);

        $this->redirect('login');
    }
}