<?php

namespace App\services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendEmail(
        $from = 'aymen.noreply@gmail.com',
        $to = 'aymen.sellaouti@gmail.com',
        $subject = 'Fromation Sf6 DigitalVirgo',
        $template = '<h1> Test Mailer </h1>'
    ) {
        $email = (new Email())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($template);
        $this->mailer->send($email);
    }

}