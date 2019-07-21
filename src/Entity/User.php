<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\ UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;




/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{

    const ROLE_USER='ROLE_USER';
    const ROLE_ADMIN='ROLE_ADMIN';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

   /**
    *@var array
     * @ORM\Column(type="simple_array")
     */
    private $roles=['ROLE_USER'];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fullname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $microPosts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     */
    private $followers;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="following",
     * joinColumns={
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     * },
     * inverseJoinColumns={
     * @ORM\JoinColumn(name="following_user_id",referencedColumnName="id")
     * }
     * )
     * 
     */
    private $following;

 /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Micropost", mappedBy="Likedby")
     * 
     */
   private $postsliked;

    public function __construct()
    {
        $this->microPosts = new ArrayCollection();
        $this->followers = new ArrayCollection();
         $this->following = new ArrayCollection();
         $this->postsliked = new ArrayCollection();
    }
     public function getFollowers()
    {
        return $this->followers;
    }
     public function getFollowing()
    {
        return $this->following;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

   public function getRoles(): array
    {
     
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }
        return $this->roles;


    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return Collection|MicroPost[]
     */
    public function getMicroPosts(): Collection
    {
        return $this->microPosts;
    }

    public function addMicroPost(MicroPost $microPost): self
    {
        if (!$this->microPosts->contains($microPost)) {
            $this->microPosts[] = $microPost;
            $microPost->setUser($this);
        }

        return $this;
    }

    public function removeMicroPost(MicroPost $microPost): self
    {
        if ($this->microPosts->contains($microPost)) {
            $this->microPosts->removeElement($microPost);
            // set the owning side to null (unless already changed)
            if ($microPost->getUser() === $this) {
                $microPost->setUser(null);
            }
        }

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function getpostsliked(): string
    {
        return  $this->postsliked;
    }
}
