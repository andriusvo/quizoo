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

namespace App\Entity\Quiz;

use App\Entity\Group\StudentGroup;
use App\Entity\Subject\Subject;
use App\Entity\User\User;
use App\Model\Traits\TimestampableTrait;
use App\Model\Traits\ToggleableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_quiz")
 *
 * @UniqueEntity(fields={"code"})
 */
class Quiz implements ResourceInterface, TimestampableInterface
{
    use TimestampableTrait;
    use ToggleableTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual("now")
     */
    private $validFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual("now")
     */
    private $validTo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=30)
     */
    private $code;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $title;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject\Subject")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $subject;

    /**
     * @var ArrayCollection|Question[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\Question",  mappedBy="quiz", cascade={"persist"})
     *
     * @Assert\Count(min=1)
     * @Assert\Valid()
     */
    private $questions;

    /**
     * @var ArrayCollection|StudentGroup[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Group\StudentGroup", cascade={"persist"})
     * @ORM\JoinTable(name="app_quiz_student_group",
     *      joinColumns={@ORM\JoinColumn(name="quiz_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="student_group_id", referencedColumnName="id")}
     *      )
     *
     * @Assert\Count(min=1)
     */
    private $groups;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValidFrom(): ?\DateTime
    {
        return $this->validFrom;
    }

    public function setValidFrom(?\DateTime $validFrom): Quiz
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    public function getValidTo(): ?\DateTime
    {
        return $this->validTo;
    }

    public function setValidTo(?\DateTime $validTo): Quiz
    {
        $this->validTo = $validTo;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): Quiz
    {
        $this->code = $code;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): Quiz
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Quiz
    {
        $this->title = $title;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(Subject $subject): Quiz
    {
        $this->subject = $subject;

        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): Quiz
    {
        if (false === $this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): Quiz
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            $question->setQuiz(null);
        }

        return $this;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(StudentGroup $studentGroup): Quiz
    {
        if (false === $this->groups->contains($studentGroup)) {
            $this->groups->add($studentGroup);
        }

        return $this;
    }

    public function removeGroup(StudentGroup $studentGroup): Quiz
    {
        if ($this->groups->contains($studentGroup)) {
            $this->groups->removeElement($studentGroup);
        }

        return $this;
    }

    public function quizTime(): int
    {
        $validFrom = $this->getValidFrom();
        $validTo = $this->getValidTo();

        if (null === $validFrom) {
            return 0;
        }

        return $validFrom->diff($validTo)->i;
    }

    public function __toString(): string
    {
        return $this->getSubject() . ': ' . $this->getTitle();
    }
}
