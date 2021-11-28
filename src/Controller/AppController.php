<?php

namespace App\Controller;

use App\DAO\TestimonialManager;
use App\Model\Testimonial;
use Twig\Environment;

class AppController extends AbstractController
{
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
    }

    public function index(): string
    {
        return $this->render('App/index.html.twig');
    }

    public function contact(): string
    {
        $moduleName = $_GET['module'] ?? 'contact.html';
        $module = file_get_contents(__DIR__."/../../templates/$moduleName");

        return $this->render('App/contact.html.twig', ['module' => $module]);
    }

    public function jwt(): string
    {
        if ($_POST && $_POST['formSubmit']) {
            if (!$token = $_POST['token']) {
                $this->redirect('jwt-checker');
            }

            $payload = explode('.', $token)[1];
            $payload = base64_decode($payload);

            if ('admin' === json_decode($payload, true)['rank']) {
                $flag = 'LV_JSON_WEB_TOKEN_ALG_N0NE';
            }
        }

        return $this->render('App/jwt-checker.html.twig', ['flag' => $flag ?? '']);
    }

    public function mailSignature(): string
    {
        if ($_POST && $_POST['formSubmit']) {
            $name = $_POST['name'];

            $template = "%s<br>ECorp Engineer<br><br>A108 Adam Street<br>New York, NY 535022<br>United States<br><br>Phone: +1 5589 55488 55<br>Email: contact@ecorp.com";
            $template = str_replace('%s', $name ?? '', $template);
            file_put_contents(__DIR__."/../../templates/include/mail_template.html.twig", $template);
            $templateMail = $this->render('include/mail_template.html.twig');
        }

        return $this->render('App/mail_signature.html.twig', ['templateMail' => $templateMail ?? null]);
    }

    public function uploadOne(): string
    {
        if ($_POST && $_POST['fileSubmit']) {
            $extension = pathinfo($_FILES['imageFile']['name'], PATHINFO_EXTENSION);

            do {
                $_FILES['imageFile']['name'] = rand(1, 999).'.'.$extension;
                $targetFile = __DIR__."/../../public/uploads/waiting/".$_FILES['imageFile']['name'];
            } while (file_exists($targetFile));

            move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetFile);
        }

        return $this->render('App/upload_one.html.twig');
    }

    public function uploadTwo(): string
    {
        if ($_POST && $_POST['fileSubmit']) {
            $extension = pathinfo($_FILES['imageFile']['name'], PATHINFO_EXTENSION);

            if (!in_array($extension, ['php', 'php5', 'phtml', 'php7', 'php4', 'php3'])) {
                $_FILES['imageFile']['name'] = uniqid().'.'.$extension;
                $targetFile = __DIR__."/../../public/uploads/landscape/".$_FILES['imageFile']['name'];

                if (!file_exists($targetFile)) {
                    move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetFile);
                }
            }
        }

        if (glob(__DIR__."/../../public/uploads/landscape/*.phar")) {
            $flag = 'LV_UPL0AD_WITH_EXTENSION_CHECK';
        }

        return $this->render('App/upload_two.html.twig', ['flag' => $flag ?? null]);
    }

    public function uploadThree(): string
    {
        if ($_POST && $_POST['fileSubmit']) {
            if ('image/jpeg' === mime_content_type($_FILES['imageFile']['tmp_name'])) {
                $targetFile = __DIR__."/../../public/uploads/afterwork/".$_FILES['imageFile']['name'];

                if (!file_exists($targetFile)) {
                    move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetFile);
                }
            }
        }

        if (glob(__DIR__."/../../public/uploads/afterwork/*.php")) {
            $flag = 'LV_UPL0AD_MAG1C_NUMB3RS_CHECK';
        }

        return $this->render('App/upload_three.html.twig', ['flag' => $flag ?? null]);
    }

    public function tools(): string
    {
        return $this->render('App/tools.html.twig');
    }

    public function search(): string
    {
        return $this->render('App/search.html.twig');
    }

    public function reset(): string
    {
        $testimonialManager = new TestimonialManager();
        $testimonials = $testimonialManager->findAll();
        foreach ($testimonials as $testimonial) {
            $testimonialManager->delete($testimonial);
        }

        $this->redirect('tools');
    }

    public function testimonials(): string
    {
        $testimonialManager = new TestimonialManager();

        if (!empty($_POST)) {
            if (empty($_POST['name'] )|| empty($_POST['role']) || empty($_POST['content'])) {
                $this->redirect('testimonials');
            }

            $object = new Testimonial();
            $object
                ->setName($_POST['name'])
                ->setRole($_POST['role'])
                ->setMessage($_POST['content'])
            ;
            $testimonialManager->create($object);

            $this->redirect('testimonials');
        }

        $testimonials = $testimonialManager->findAll();

        return $this->render('App/testimonials.html.twig', ['testimonials' => $testimonials]);
    }
}