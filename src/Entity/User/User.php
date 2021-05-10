<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Group\StudentGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Platform\Bundle\AdminBundle\Model\AdminUser;
use Sylius\Component\Rbac\Model\IdentityInterface;
use Sylius\Component\Rbac\Model\Role;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_user")
 */
class User extends AdminUser implements IdentityInterface
{
    /**
     * @var ArrayCollection|Role[]
     *
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Rbac\Model\Role", cascade={"persist"})
     * @ORM\JoinTable(name="app_user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     *
     * @Assert\Count(min="1")
     */
    private $authorizationRoles;

    /**
     * @var null|StudentGroup
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Group\StudentGroup", inversedBy="students", cascade={"persist"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $group;

    public function __construct()
    {
        parent::__construct();

        $this->authorizationRoles = new ArrayCollection();
    }

    /** @return ArrayCollection|Role[] */
    public function getAuthorizationRoles(): Collection
    {
        return $this->authorizationRoles;
    }

    public function addAuthorizationRole(Role $role): User
    {
        if (false === $this->authorizationRoles->contains($role)) {
            $this->authorizationRoles->add($role);
        }

        return $this;
    }

    /** @return Role[] */
    public function getRoles(): array
    {
        $roles = [];

        foreach ($this->getAuthorizationRoles() as $authorizationRole) {
            $roles = array_merge($roles, $authorizationRole->getSecurityRoles());
        }

        return $roles;
    }

    /** @param iterable $authorizationRoles */
    public function setAuthorizationRoles($authorizationRoles): User
    {
        $this->authorizationRoles = $authorizationRoles;

        return $this;
    }

    public function getGroup(): ?StudentGroup
    {
        return $this->group;
    }

    public function setGroup(?StudentGroup $group): User
    {
        $this->group = $group;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }
}
