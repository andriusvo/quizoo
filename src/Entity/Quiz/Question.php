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

use App\Constants\QuestionTypes;
use App\Validator\Constraints\QuestionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_question")
 *
 * @QuestionType()
 */
class Question implements ResourceInterface
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
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank()
     */
    private $type = QuestionTypes::SINGLE_ANSWER;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var Quiz
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz\Quiz", inversedBy="questions", cascade={"persist"})
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", nullable=false)
     */
    private $quiz;

    /**
     * @var Answer[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Quiz\Answer",  mappedBy="question", cascade={"persist"})
     *
     * @Assert\Valid()
     * @Assert\Count(min=1)
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): Question
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Question
    {
        $this->title = $title;

        return $this;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): Question
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): Question
    {
        if (false === $this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): Question
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            $answer->setQuestion(null);
        }

        return $this;
    }

    public function countCorrectAnswers(): int
    {
        $count = 0;

        foreach ($this->answers as $answer) {
            if ($answer->isCorrect()) {
                $count++;
            }
        }

        return $count;
    }

    public function getCorrectAnswers(): Collection
    {
        return $this->answers->filter(
            function (Answer $answer): bool {
                return $answer->isCorrect();
            }
        );
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
