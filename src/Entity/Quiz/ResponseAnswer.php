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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Quiz\Answer")
     * @ORM\JoinTable(name="reponse_quiz_answer",
     *      joinColumns={@ORM\JoinColumn(name="response_answer_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="answer_id", referencedColumnName="id", unique=true)}
     * )
     */
    private $selectedAnswers;

    /**
     * @var Response
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Response", inversedBy="answers", cascade={"persist"})
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id", nullable=false)
     */
    private $response;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $correct = false;

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

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): ResponseAnswer
    {
        $this->correct = $correct;

        return $this;
    }

    public function getSelectedAnswer(): Answer
    {
        return $this->selectedAnswers->first();
    }
}
