<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Entity\Quiz\Quiz;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class NewQuizMailer
{
    /** @var Mailer */
    private $mailer;

    /** @var TranslatorInterface */
    private $translator;

    /** @var Environment */
    private $twig;

    public function __construct(Mailer $mailer, TranslatorInterface $translator, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->twig = $twig;
    }

    public function sendQuizNotificationMail(Quiz $quiz): void
    {
        $subject = $this->translator->trans('app.email.new_quiz');
        $body = $this->twig->render('@PlatformAdmin/Email/new_quiz.html.twig', ['quiz' => $quiz]);

        foreach ($quiz->getGroups() as $studentGroup) {
            foreach ($studentGroup->getStudents() as $student) {
                $this->mailer->send($subject, \getenv('APP_EMAIL'), $student->getEmail(), $body);
            }
        }
    }
}
