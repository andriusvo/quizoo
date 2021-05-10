<?php

declare(strict_types=1);

namespace App\Mailer;

class Mailer
{
    private const CONTENT_TYPE = 'text/html';

    /** @var \Swift_Mailer */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $subject, string $form, ?string $to, string $body): void
    {
        if (null === $to) {
            return;
        }

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($form)
            ->setTo($to)
            ->setBody($body, self::CONTENT_TYPE);

        $this->mailer->send($message);
    }
}
