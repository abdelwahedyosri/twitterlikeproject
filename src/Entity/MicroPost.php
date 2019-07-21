<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * @Assert\Length(min=5)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="microPosts")
     */
    private $user;

     /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User",inversedBy="postsliked")
     * * @ORM\JoinTable(name="postlikes",
     * joinColumns={
     * @ORM\JoinColumn(name="post_id",referencedColumnName="id")
     * },
     * inverseJoinColumns={
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     * }
     * )
     */

    private $Likedby;
     public function __construct()
    {
        $this->Likedby = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
     public function getLikedBy()
    {
        return $this->Likedby;
    }
    
    public function liked(User $user){
        if($this->Likedby->contains($user)){
            return;
        }else{
            $this->Likedby->add($user);
        }
    }
}
