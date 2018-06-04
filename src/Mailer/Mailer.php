<?php

namespace App\Mailer;


use App\Entity\User;
use Swift_Mailer;
use Twig_Environment;

class Mailer
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Twig_Environment
     */
    protected $twig;
    /**
     * @var string
     */
    protected $mailFrom;

    /**
     * Mailer constructor.
     * @param Swift_Mailer $mailer
     * @param Twig_Environment $twig
     * @param string $mailFrom
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render('email/registration.html.twig', [
            'user' => $user
        ]);

        $message = (new \Swift_Message())
            ->addFrom($this->mailFrom)
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html')
            ->setSubject('Welcome to the MicroPost App');

        $this->mailer->send($message);
    }
}