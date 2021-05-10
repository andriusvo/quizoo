<?php

declare(strict_types=1);

namespace App\Entity\Group;

use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_student_group")
 *
 * @UniqueEntity(fields={"code"})
 */
class StudentGroup implements ResourceInterface, CodeAwareInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=255)
     *
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var User[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\User\User",  mappedBy="group", cascade={"persist"})
     *
     * @Assert\Count(min=1)
     */
    private $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $student): StudentGroup
    {
        if (false === $this->students->contains($student)) {
            $this->students->add($student);
            $student->setGroup($this);
        }

        return $this;
    }

    public function removeStudent(User $student): StudentGroup
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
            $student->setGroup(null);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getCode();
    }
}
