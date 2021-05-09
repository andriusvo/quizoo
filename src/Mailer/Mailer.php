<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * https://www.nfq.lt
 */

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
