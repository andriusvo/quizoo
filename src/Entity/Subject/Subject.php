<?php

declare(strict_types=1);

namespace App\Entity\Subject;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_subject")
 *
 * @UniqueEntity(fields={"code"})
 */
class Subject implements ResourceInterface, CodeAwareInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="supervisor_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $supervisor;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $title;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode($code): void
    {
        $this->code = $code;
    }

    public function getSupervisor(): ?User
    {
        return $this->supervisor;
    }

    public function setSupervisor(User $supervisor): Subject
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Subject
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
