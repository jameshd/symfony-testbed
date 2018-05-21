<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min="10", minMessage="Needs to be at least 10 chararters")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false,fieldName="user_id")
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function getText():  ? string
    {
        return $this->text;
    }

    public function setText(string $text) : self
    {
        $this->text = $text;

        return $this;
    }

    public function getTime():  ? \DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time) : self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return App\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
