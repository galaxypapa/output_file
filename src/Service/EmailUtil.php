<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailUtil implements EmailUtilInterface
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * Send email with CSV file, XML etc.
     *
     * @param string $recipient
     * @param array $attachments
     */
    public function sendEmail(string $recipient, array $attachments): void
    {
        $email = (new Email())
            ->from('csv@example.com')
            ->to($recipient)
            ->cc('cc@example.com')
            ->bcc('bcc@example.com')
            ->subject('CSV file attachment!')
            ->text('CSV file attachment!')
            ->html('<p>CSV file  HTML content!</p>');
        foreach ($attachments as $attachment) {
            $email->attachFromPath($attachment);
        }
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface | \Exception $e) {
            var_dump($e);
        }
    }
}