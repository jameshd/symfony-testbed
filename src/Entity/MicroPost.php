<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
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


    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="postsLiked")
     * @ORM\JoinTable(
     *     name="post_likes",
     *     joinColumns={
     *      @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     }
     *  )
     */
    private $likedBy;

    /**
     * @return Collection
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }


    public function like(User $user)
    {
        if (! $this->getLikedBy()->contains($user)) {
            return $this->getLikedBy()->add($user);
        }

        return false;
    }

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

    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist()
    {
        $this->setTime(new \DateTime());
    }
}
