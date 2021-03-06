<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource (
 *     collectionOperations={
 *           "get"={
 *              "security"="is_granted('ROLE_USER')",
 *              "security_message"="You must be logged In to see other member's profile",
 *              "path"="/secure/users"
 *          },
 *          "post"={
 *              "path"="/users",
 *              "validation_groups"={"Default", "register"}
 *          },
 *     },
 *     itemOperations={
 *           "get" ={
 *              "security"="is_granted('ROLE_USER')",
 *              "security_message"="You must be logged in to see user's profile",
 *              "path"="/secure/users/{id}.{_format}"
 *          },
 *          "put"={
 *              "security"="is_granted('ROLE_USER') and object.owner == user",
 *              "security_message"="You must be logged in to update your profile",
 *              "path"="/secure/users/{id}.{_format}"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="You cannot delete a profile unless admin",
 *              "path"="/secure/users/{id}.{_format}"
 *          }
 *     },
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}}
 *     )
 * @ApiFilter(SearchFilter::class, properties={"email":"exact"})
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cette adresse email est d??j?? utilis??e")
 * @UniqueEntity (fields={"username"},
 *     message="Ce pseudonyme n'est plus disponible")
 */
class User implements JWTUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups ({"user:read", "user:write"})
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups({"user:write"})
     * @SerializedName("password")
     * @Assert\NotBlank(groups={"register"})
     * @Assert\Length(
     *     min=8,
     *     minMessage="Password too short"
     * )
     */
    private $plainPassword;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"user:read", "user:write"})
     * @Assert\NotBlank
     *
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"user:read", "user:write"})
     * @Groups ({"training:read"})
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"user:read", "user:write"})
     * @Assert\NotBlank
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity=Training::class, mappedBy="owner", orphanRemoval=true)
     * @Groups ({"user:read"})
     */
    private $trainings;

    /**
     * @ORM\ManyToMany(targetEntity=Training::class, mappedBy="subscribers")
     * @Groups ({"user:read"})
     */
    private $trainingSubscriptions;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phonenumber;

    public function __construct($username, $email)
    {
        $this->username = $username;
        $this->roles = ['ROLE_USER'];
        $this->email = $email;
        $this->trainings = new ArrayCollection();
        $this->trainingSubscriptions = new ArrayCollection();
    }

    public static function createFromPayload($username, array $payload): User
    {
        return new self(
            $username,
            $payload['roles'], // Added by default
            $payload['email']  // Custom
        );
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
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = 'User';

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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|Training[]
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): self
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings[] = $training;
            $training->setOwner($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): self
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getOwner() === $this) {
                $training->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Training[]
     */
    public function getTrainingSubscriptions(): Collection
    {
        return $this->trainingSubscriptions;
    }

    public function addTrainingSubscription(Training $trainingSubscription): self
    {
        if (!$this->trainingSubscriptions->contains($trainingSubscription)) {
            $this->trainingSubscriptions[] = $trainingSubscription;
            $trainingSubscription->addSubscriber($this);
        }

        return $this;
    }

    public function removeTrainingSubscription(Training $trainingSubscription): self
    {
        if ($this->trainingSubscriptions->removeElement($trainingSubscription)) {
            $trainingSubscription->removeSubscriber($this);
        }

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;
        return $this;
    }
    public function __toString ()
    {
        return (string) $this->email;
    }


    public function getPlainPassword() :?string
    {
        return $this->plainPassword;
    }


    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}
