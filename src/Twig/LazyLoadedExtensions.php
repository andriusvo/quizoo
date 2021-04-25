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
        ];
    }
}
