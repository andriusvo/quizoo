<?php

declare(strict_types=1);

namespace App\Model\Traits;

use Doctrine\ORM\Mapping as ORM;

trait PublicIdentityTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="guid", unique = true)
     */
    private $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
