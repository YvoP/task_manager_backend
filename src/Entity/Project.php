<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $discordServer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $discordChannel = null;

    #[ORM\Column]
    private ?bool $autoRecordDiscordMeeting = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isArchived = null;

    /**
     * @var Collection<int, ProjectUser>
     */
    #[ORM\OneToMany(targetEntity: ProjectUser::class, mappedBy: 'project')]
    private Collection $projectUsers;

    #[ORM\OneToOne(mappedBy: 'project', cascade: ['persist', 'remove'])]
    private ?Chat $chat = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project')]
    private Collection $tasks;

    /**
     * @var Collection<int, Meeting>
     */
    #[ORM\OneToMany(targetEntity: Meeting::class, mappedBy: 'project')]
    private Collection $meetings;

    public function __construct()
    {
        $this->projectUsers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDiscordServer(): ?string
    {
        return $this->discordServer;
    }

    public function setDiscordServer(?string $discordServer): static
    {
        $this->discordServer = $discordServer;

        return $this;
    }

    public function getDiscordChannel(): ?string
    {
        return $this->discordChannel;
    }

    public function setDiscordChannel(?string $discordChannel): static
    {
        $this->discordChannel = $discordChannel;

        return $this;
    }

    public function isAutoRecordDiscordMeeting(): ?bool
    {
        return $this->autoRecordDiscordMeeting;
    }

    public function setAutoRecordDiscordMeeting(bool $autoRecordDiscordMeeting): static
    {
        $this->autoRecordDiscordMeeting = $autoRecordDiscordMeeting;

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

    public function isArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    /**
     * @return Collection<int, ProjectUser>
     */
    public function getProjectUsers(): Collection
    {
        return $this->projectUsers;
    }

    public function addProjectUser(ProjectUser $projectUser): static
    {
        if (!$this->projectUsers->contains($projectUser)) {
            $this->projectUsers->add($projectUser);
            $projectUser->setProject($this);
        }

        return $this;
    }

    public function removeProjectUser(ProjectUser $projectUser): static
    {
        if ($this->projectUsers->removeElement($projectUser)) {
            // set the owning side to null (unless already changed)
            if ($projectUser->getProject() === $this) {
                $projectUser->setProject(null);
            }
        }

        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): static
    {
        // unset the owning side of the relation if necessary
        if ($chat === null && $this->chat !== null) {
            $this->chat->setProject(null);
        }

        // set the owning side of the relation if necessary
        if ($chat !== null && $chat->getProject() !== $this) {
            $chat->setProject($this);
        }

        $this->chat = $chat;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): static
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings->add($meeting);
            $meeting->setProject($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): static
    {
        if ($this->meetings->removeElement($meeting)) {
            // set the owning side to null (unless already changed)
            if ($meeting->getProject() === $this) {
                $meeting->setProject(null);
            }
        }

        return $this;
    }
}
