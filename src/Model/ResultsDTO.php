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

namespace App\Model;

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
