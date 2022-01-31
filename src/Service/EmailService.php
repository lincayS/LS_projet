<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
public $coeur;

    public function __construct(MailerInterface $mailerIntarface)
    {
        $this->coeur = $mailerIntarface;
    }

    public function envoyer(string $from, string $destinataire, string $objet,string $vue, array $table): void
    {

        $email = (new TemplatedEmail())
            ->from($from)
            ->to( $destinataire)
            ->subject($objet)
    
            // path of the Twig template to render
            ->htmlTemplate($vue)
            ->context($table);
    
            $this->coeur->send($email);

    }        

}