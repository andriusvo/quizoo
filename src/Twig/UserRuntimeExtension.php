<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User\User;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Extension\RuntimeExtensionInterface;

class UserRuntimeExtension implements RuntimeExtensionInterface
{
    private $userRepository;

    public function __construct(RepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }
}
