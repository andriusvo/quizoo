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
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace App\Menu;

use App\Constants\AuthorizationRoles;
use App\Entity\User\User;
use App\Provider\UserProvider;
use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppMenuSubscriber implements EventSubscriberInterface
{
    /** @var UserProvider */
    private $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /** {@inheritdoc} */
    public static function getSubscribedEvents(): array
    {
        return [
            'admin_platform.menu.main' => 'addMenuItems',
        ];
    }

    public function addMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $this->configureQuizMenu($menu);
        $this->configureStudentsMenu($menu);
        $this->configureConfigurationMenu($menu);
    }

    private function configureQuizMenu(ItemInterface $menuItem): void
    {
        $quizMenu = $menuItem
            ->addChild('quiz_list')
            ->setLabel('app.ui.quiz_configuration');

        $quizMenu
            ->addChild('quizzes', ['route' => 'app_quiz_index'])
            ->setLabel('app.ui.quizzes')
            ->setLabelAttribute('icon', 'question circle outline');
    }

    private function configureConfigurationMenu(ItemInterface $menuItem): void
    {
        /** @var User $user */
        $user = $this->userProvider->getUser();

        if (false === $user->hasRole(AuthorizationRoles::ROLE_SUPERADMIN)) {
            $menuItem->removeChild('configuration');

            return;
        }

        $configurationMenu = $menuItem->getChild('configuration');

        if (null === $configurationMenu) {
            return;
        }

        $configurationMenu->removeChild('locales');

        $configurationMenu
            ->addChild('teachers', ['route' => 'app_teacher_index'])
            ->setLabel('app.ui.teachers')
            ->setLabelAttribute('icon', 'user circle outline');

        $configurationMenu
            ->addChild('subjects', ['route' => 'app_subject_index'])
            ->setLabel('app.ui.subjects')
            ->setLabelAttribute('icon', 'address book outline');
    }

    private function configureStudentsMenu(ItemInterface $menuItem): void
    {
        $studentMenu = $menuItem
            ->addChild('students')
            ->setLabel('app.ui.students');

        $studentMenu
            ->addChild('students_list', ['route' => 'app_admin_student_index'])
            ->setLabel('app.ui.students')
            ->setLabelAttribute('icon', 'user');

        $studentMenu
            ->addChild('student_group', ['route' => 'app_student_group_index'])
            ->setLabel('app.ui.student_group')
            ->setLabelAttribute('icon', 'suitcase');
    }
}
