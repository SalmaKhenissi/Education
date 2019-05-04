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

    public function notify(Contact $contact , $repo , $repoI ,$repoPi)
    {
        $message = (new \Swift_Message('Subject : ' . $contact->getSubject()))
            ->setFrom($contact->getEmail())
            ->setTo('salmakhenissi19@gmail.com')
            ->setBody($this->renderer->render('Front/Guest/home.html.twig', [
                'contact' => $contact ,
                'parameters' =>$repo->find(1),
                'slider1' => $repoI->find(1),
                'slider2' => $repoI->find(2),
                'slider3' => $repoI->find(3),
                'service1' => $repoI->find(4),
                'service2' => $repoI->find(5),
                'service3' => $repoI->find(6),
                'service4' => $repoI->find(7),
                'pictures' => $repoPi->findAll()
            ]), 'text/html');
        $this->mailer->send($message);
        if ($this->mailer->send($message)) {
            echo '[SWIFTMAILER] sent email from ' . $contact->getEmail();
        } else {
            echo '[SWIFTMAILER] not sending email: ';
        }
    }
}