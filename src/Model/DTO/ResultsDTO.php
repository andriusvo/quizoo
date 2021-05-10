<?php

declare(strict_types=1);

namespace App\Model\DTO;

use App\Entity\Group\StudentGroup;
use App\Entity\User\User;
use App\Validator\Constraints\ResultsGeneration;

/** @ResultsGeneration() */
class ResultsDTO
{
    /** @var StudentGroup|null */
    private $studentGroup;

    /** @var User|null */
    private $student;

    public function getStudentGroup(): ?StudentGroup
    {
        return $this->studentGroup;
    }

    public function setStudentGroup(?StudentGroup $studentGroup): ResultsDTO
    {
        $this->studentGroup = $studentGroup;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): ResultsDTO
    {
        $this->student = $student;

        return $this;
    }
}
