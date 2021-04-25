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

use App\Entity\Subject\Subject;
use App\Entity\User\User;
use App\Model\TimestampableTrait;
use App\Model\ToggleableTrait;
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
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
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
     * @var Question[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\Question",  mappedBy="quiz", cascade={"persist"})
     *
     * @Assert\Count(min=1)
     * @Assert\Valid()
     */
    private $questions;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $manualEvaluation = false;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValidFrom(): ?\DateTime
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTime $validFrom): Quiz
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    public function getValidTo(): ?\DateTime
    {
        return $this->validTo;
    }

    public function setValidTo(\DateTime $validTo): Quiz
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

    public function isManualEvaluation(): bool
    {
        return $this->manualEvaluation;
    }

    public function setManualEvaluation(bool $manualEvaluation): Quiz
    {
        $this->manualEvaluation = $manualEvaluation;

        return $this;
    }
}
