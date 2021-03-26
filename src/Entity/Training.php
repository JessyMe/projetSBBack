<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"training:read"}},
 *     denormalizationContext={"groupes"={"training:write"}},
 * )
 * @ORM\Entity(repositoryClass=TrainingRepository::class)
 *
 */
class Training
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="date")
     */
    private $dateTraining;

    /**
     * @ORM\Column(type="time")
     */
    private $timestart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $timeEnd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trainings")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Groups ({"training:read", "training:write"})
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="trainingSubscriptions")
     * @Groups ({"training:read", "training:write"})
     */
    private $subscribers;

    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDateTraining(): ?\DateTimeInterface
    {
        return $this->dateTraining;
    }

    public function setDateTraining(\DateTimeInterface $dateTraining): self
    {
        $this->dateTraining = $dateTraining;

        return $this;
    }

    public function getTimestart(): ?\DateTimeInterface
    {
        return $this->timestart;
    }

    public function setTimestart(\DateTimeInterface $timestart): self
    {
        $this->timestart = $timestart;

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(?\DateTimeInterface $timeEnd): self
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }

    public function addSubscriber(User $subscriber): self
    {
        if (!$this->subscribers->contains($subscriber)) {
            $this->subscribers[] = $subscriber;
        }

        return $this;
    }

    public function removeSubscriber(User $subscriber): self
    {
        $this->subscribers->removeElement($subscriber);

        return $this;
    }
}
