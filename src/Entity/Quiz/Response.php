<?php

declare(strict_types=1);

namespace App\Entity\Quiz;

use App\Entity\User\User;
use App\Model\Traits\PublicIdentityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_response",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idx_student_quiz_uniq", columns={"student_id", "quiz_id"}),
 * })
 *
 * @UniqueEntity(fields={"student", "quiz"})
 */
class Response implements ResourceInterface
{
    use PublicIdentityTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @var Quiz
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz", cascade={"persist"})
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", nullable=false)
     */
    private $quiz;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", nullable=false)
     */
    private $student;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishDate;

    /**
     * @var ArrayCollection|ResponseAnswer[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\ResponseAnswer",  mappedBy="response", cascade={"persist"})
     *
     * @Assert\Valid()
     */
    private $answers;

    public function __construct()
    {
        $this->uuid = Uuid::uuid1()->toString();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): Response
    {
        $this->score = $score;

        return $this;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): Response
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getStudent(): User
    {
        return $this->student;
    }

    public function setStudent(User $student): Response
    {
        $this->student = $student;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): Response
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinishDate(): ?\DateTime
    {
        return $this->finishDate;
    }

    public function setFinishDate(?\DateTime $finishDate): Response
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function getFinishTime(): int
    {
        $startDate = $this->getStartDate();
        $finishDate = $this->getFinishDate();

        if (null === $startDate) {
            return 0;
        }

        return $startDate->diff($finishDate)->i;
    }

    public function getTotalScore(): int
    {
        $score = 0;

        /** @var ResponseAnswer $responseAnswer */
        foreach ($this->getAnswers() as $responseAnswer) {
            foreach ($responseAnswer->getQuestion()->getCorrectAnswers() as $correctAnswer) {
                $score += 100;
            }
        }

        return $score;
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(ResponseAnswer $responseAnswer): Response
    {
        if (false === $this->answers->contains($responseAnswer)) {
            $this->answers->add($responseAnswer);
            $responseAnswer->setResponse($this);
        }

        return $this;
    }

    public function removeAnswer(ResponseAnswer $responseAnswer): Response
    {
        if ($this->answers->contains($responseAnswer)) {
            $this->answers->removeElement($responseAnswer);
            $responseAnswer->setResponse(null);
        }

        return $this;
    }
}
