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

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppMenuSubscriber implements EventSubscriberInterface
{
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
        $this->configureConfigurationMenu($menu);
        $this->configureStudentsMenu($menu);
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
        $configurationMenu = $menuItem->getChild('configuration');

        if (null === $configurationMenu) {
            return;
        }

        $configurationMenu->removeChild('locales');
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
            ->addChild('student_group', ['route' => 'app_student_group_index'])
            ->setLabel('app.ui.student_group')
            ->setLabelAttribute('icon', 'user');
    }
}
