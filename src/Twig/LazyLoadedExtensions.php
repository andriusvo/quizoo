<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LazyLoadedExtensions extends AbstractExtension
{
    /** {@inheritdoc} */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_get_user_by_id', [UserRuntimeExtension::class, 'getUserById']),
            new TwigFunction('app_upcoming_quizzes', [QuizRuntimeExtension::class, 'findUpcomingQuizzes']),
            new TwigFunction('app_finished_quizzes', [QuizRuntimeExtension::class, 'findFinishedQuizzes']),
        ];
    }
}
