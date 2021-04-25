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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_question")
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
     */
    private $type = QuestionTypes::SINGLE_ANSWER;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
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

    public function setQuiz(Quiz $quiz): Question
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
}
