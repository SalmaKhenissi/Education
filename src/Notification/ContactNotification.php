<?php
namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;
class ContactNotification
{
    private $mailer;
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact , $repo , $repoD ,$repoPi , $tabP , $tabE)
    {
        $message = (new \Swift_Message('Subject : ' . $contact->getSubject()))
            ->setFrom($contact->getEmail())
            ->setTo('salmakhenissi19@gmail.com')
            ->setBody($this->renderer->render('Front/Guest/home.html.twig', [
                'contact' => $contact ,
                'parameters' => $repo->find(1),
                'service1' => $repoD->find(1),
                'service2' => $repoD->find(2),
                'service3' => $repoD->find(3),
                'service4' => $repoD->find(4),
                'pictures' => $tabP ,
                'events' => $tabE
            ]), 'text/html');
        $this->mailer->send($message);
        if ($this->mailer->send($message)) {
            echo '[SWIFTMAILER] sent email from ' . $contact->getEmail();
        } else {
            echo '[SWIFTMAILER] not sending email: ';
        }
    }
}