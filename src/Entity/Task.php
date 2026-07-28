<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Meeting $sourceMeeting = null;

    #[ORM\ManyToOne(inversedBy: 'createdTasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectUser $createdBy = null;

    /**
     * @var Collection<int, TaskContent>
     */
    #[ORM\OneToMany(targetEntity: TaskContent::class, mappedBy: 'task')]
    private Collection $taskHistory;

    public function __construct()
    {
        $this->taskHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getSourceMeeting(): ?Meeting
    {
        return $this->sourceMeeting;
    }

    public function setSourceMeeting(?Meeting $sourceMeeting): static
    {
        $this->sourceMeeting = $sourceMeeting;

        return $this;
    }

    public function getCreatedBy(): ?ProjectUser
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?ProjectUser $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, TaskContent>
     */
    public function getTaskHistory(): Collection
    {
        return $this->taskHistory;
    }

    public function addTaskHistory(TaskContent $taskHistory): static
    {
        if (!$this->taskHistory->contains($taskHistory)) {
            $this->taskHistory->add($taskHistory);
            $taskHistory->setTask($this);
        }

        return $this;
    }

    public function removeTaskHistory(TaskContent $taskHistory): static
    {
        if ($this->taskHistory->removeElement($taskHistory)) {
            // set the owning side to null (unless already changed)
            if ($taskHistory->getTask() === $this) {
                $taskHistory->setTask(null);
            }
        }

        return $this;
    }
}
