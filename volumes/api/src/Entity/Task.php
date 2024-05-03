<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Constants\TaskPriorityEnum;
use App\Constants\TaskStateEnum;
use App\Repository\TaskRepository;
use App\State\TaskCreateProvider;
use App\State\TaskCreator;
use App\State\TaskUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        normalizationContext: ['groups' => ['task:read']],
    ),
    new Get(
        security: "object.user == user"
    ),
    new Post(
        normalizationContext: ['groups' => ['task:read']],
        denormalizationContext: ['groups' => ['task:create']],
        processor: TaskCreator::class
    ),
    new Delete(),
    new Patch(processor: TaskUpdater::class),
    new Put(processor: TaskUpdater::class),
])]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['task:create', 'task:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['task:create', 'task:read'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[Groups(['task:read'])]
    public ?User $user = null;

    #[ORM\Column(type: 'string', length: 30, enumType: TaskStateEnum::class)]
    #[Groups(['task:create', 'task:read'])]
    private ?TaskStateEnum $status = TaskStateEnum::TODO;

    #[ORM\Column(type: 'integer', enumType: TaskPriorityEnum::class)]
    #[Groups(['task:create', 'task:read'])]
    private ?TaskPriorityEnum $priority = TaskPriorityEnum::THIRD;

    #[ORM\Column]
    #[Groups(['task:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['task:read'])]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subTask')]
    #[Groups(['task:create'])]
    private ?self $parentTask = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentTask')]
    #[Groups(['task:read'])]
    private Collection $subTask;

    public function __construct()
    {
        $this->subTask = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?TaskStateEnum
    {
        return $this->status;
    }

    public function setStatus(TaskStateEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?TaskPriorityEnum
    {
        return $this->priority;
    }

    public function setPriority(TaskPriorityEnum $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getParentTask(): ?self
    {
        return $this->parentTask;
    }

    public function setParentTask(?self $parentTask): static
    {
        $this->parentTask = $parentTask;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubTask(): Collection
    {
        return $this->subTask;
    }

    public function addSubTask(self $subTask): static
    {
        if (!$this->subTask->contains($subTask)) {
            $this->subTask->add($subTask);
            $subTask->setParentTask($this);
        }

        return $this;
    }

    public function removeSubTask(self $subTask): static
    {
        if ($this->subTask->removeElement($subTask)) {
            // set the owning side to null (unless already changed)
            if ($subTask->getParentTask() === $this) {
                $subTask->setParentTask(null);
            }
        }

        return $this;
    }
}
