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

use App\Model\TimestampableTrait;
use Platform\Bundle\AdminBundle\Model\AdminUser;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Doctrine\ORM\Mapping as ORM;

class Quiz implements ResourceInterface, TimestampableInterface
{
    use TimestampableTrait;

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
     */
    private $validFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $validTo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $code;

    /**
     * @var AdminUser
     *
     * @ORM\ManyToOne(targetEntity="Platform\Bundle\AdminBundle\Model\AdminUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValidFrom(): \DateTime
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTime $validFrom): Quiz
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    public function getValidTo(): \DateTime
    {
        return $this->validTo;
    }

    public function setValidTo(\DateTime $validTo): Quiz
    {
        $this->validTo = $validTo;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): Quiz
    {
        $this->code = $code;

        return $this;
    }

    public function getOwner(): AdminUser
    {
        return $this->owner;
    }

    public function setOwner(AdminUser $owner): Quiz
    {
        $this->owner = $owner;

        return $this;
    }
}
