<?php

namespace App\Mail;

use App\Entity\NewsletterSubscriber;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsletterSubscribedConfirmation
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function send(NewsletterSubscriber $newSubscriber): void
    {
        $email = (new Email())
            ->from('admin@hbcorp.com')
            ->to($newSubscriber->getEmail())
            ->subject('Merci pour votre inscription !')
            ->text('Votre inscription à la newsletter SF News a bien été prise en compte, merci !')
            ->html('<p>Votre inscription à la newsletter SF News a bien été prise en compte, merci !</p>');

        $this->mailer->send($email);
    }
}
