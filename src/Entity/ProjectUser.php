<?php

namespace App\Entity;

use App\Repository\ProjectUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProjectUserRepository::class)]
#[UniqueEntity(
    fields: ['user', 'project'],
)]
class ProjectUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'projectUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column]
    private array $permissions = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $joinedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'createdBy')]
    private Collection $createdTasks;

    /**
     * @var Collection<int, Meeting>
     */
    #[ORM\ManyToMany(targetEntity: Meeting::class, mappedBy: 'projectUsers')]
    private Collection $attendedMeetings;

    /**
     * @var Collection<int, TaskContent>
     */
    #[ORM\OneToMany(targetEntity: TaskContent::class, mappedBy: 'attributedTo')]
    private Collection $tasks;

    /**
     * @var Collection<int, File>
     */
    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'createdBy')]
    private Collection $files;

    /**
     * @var Collection<int, Chat>
     */
    #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'projectUsers')]
    private Collection $chats;

    public function __construct()
    {
        $this->createdTasks = new ArrayCollection();
        $this->attendedMeetings = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->chats = new ArrayCollection();
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): static
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeImmutable $joinedAt): static
    {
        $this->joinedAt = $joinedAt;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getCreatedTasks(): Collection
    {
        return $this->createdTasks;
    }

    public function addCreatedTask(Task $createdTask): static
    {
        if (!$this->createdTasks->contains($createdTask)) {
            $this->createdTasks->add($createdTask);
            $createdTask->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedTask(Task $createdTask): static
    {
        if ($this->createdTasks->removeElement($createdTask)) {
            // set the owning side to null (unless already changed)
            if ($createdTask->getCreatedBy() === $this) {
                $createdTask->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Meeting>
     */
    public function getAttendedMeetings(): Collection
    {
        return $this->attendedMeetings;
    }

    public function addAttendedMeeting(Meeting $attendedMeeting): static
    {
        if (!$this->attendedMeetings->contains($attendedMeeting)) {
            $this->attendedMeetings->add($attendedMeeting);
            $attendedMeeting->addUser($this);
        }

        return $this;
    }

    public function removeAttendedMeeting(Meeting $attendedMeeting): static
    {
        if ($this->attendedMeetings->removeElement($attendedMeeting)) {
            $attendedMeeting->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TaskContent>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(TaskContent $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setAttributedTo($this);
        }

        return $this;
    }

    public function removeTask(TaskContent $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getAttributedTo() === $this) {
                $task->setAttributedTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setCreatedBy($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getCreatedBy() === $this) {
                $file->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): static
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->addProjectUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): static
    {
        if ($this->chats->removeElement($chat)) {
            $chat->removeProjectUser($this);
        }

        return $this;
    }
}
