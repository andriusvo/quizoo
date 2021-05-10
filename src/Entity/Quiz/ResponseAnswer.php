<?php

declare(strict_types=1);

namespace App\Entity\Quiz;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_response_answer")
 */
class ResponseAnswer implements ResourceInterface
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Quiz\Answer", cascade={"persist"})
     * @ORM\JoinTable(name="app_response_quiz_answer",
     *      joinColumns={@ORM\JoinColumn(name="response_answer_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="answer_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @Assert\Count(min="1", minMessage="You should select at least one answer")
     */
    private $selectedAnswers;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    private $question;

    /**
     * @var Response
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Response", inversedBy="answers", cascade={"persist"})
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id", nullable=false)
     */
    private $response;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $score = 0;

    public function __construct()
    {
        $this->selectedAnswers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSelectedAnswers(): Collection
    {
        return $this->selectedAnswers;
    }

    public function addSelectedAnswer(Answer $answer): ResponseAnswer
    {
        if (false === $this->selectedAnswers->contains($answer)) {
            $this->selectedAnswers->add($answer);
        }

        return $this;
    }

    public function removeSelectedAnswer(Answer $answer): ResponseAnswer
    {
        if ($this->selectedAnswers->contains($answer)) {
            $this->selectedAnswers->removeElement($answer);
        }

        return $this;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): ResponseAnswer
    {
        $this->response = $response;

        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): ResponseAnswer
    {
        $this->question = $question;

        return $this;
    }

    public function setScore(?int $score): ResponseAnswer
    {
        $this->score = $score;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }
}
