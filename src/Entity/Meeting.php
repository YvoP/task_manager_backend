<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Dto\AnalyzeMeetingInputDto;
use App\Dto\TranscriptionInputDto;
use App\Enum\Process;
use App\Enum\Status;
use App\Repository\MeetingRepository;
use App\State\AnalyzeMeetingProvider;
use App\State\TranscriptionProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(),
        new Post(
            uriTemplate: '/meetings/{craigId}/transcription',
            uriVariables: [
                'craigId' => new Link(
                    parameterName: 'craigId',
                    fromClass: Meeting::class,
                    identifiers: ['craigId'],
                ),
            ],
            input: TranscriptionInputDto::class,
            read: false,
            processor: TranscriptionProcessor::class,
        ),
        new Get(
            uriTemplate: '/meetings/{id}/analyze',
            provider: AnalyzeMeetingProvider::class,
        ),
    ],
    mercure: true
)]
class Meeting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $transcript = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $audio = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'sourceMeeting')]
    private Collection $tasks;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Project $project = null;

    /**
     * @var Collection<int, ProjectUser>
     */
    #[ORM\ManyToMany(targetEntity: ProjectUser::class, inversedBy: 'attendedMeetings')]
    private Collection $projectUsers;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $craigId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $recordedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $craigKey = null;

    #[ORM\Column(enumType: Status::class)]
    private Status $status = Status::PENDING;

    #[ORM\Column(nullable: true, enumType: Process::class)]
    private ?Process $activeProcess = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->projectUsers = new ArrayCollection();

        $this->createdAt = new \DateTimeImmutable();
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

    public function getTranscript(): ?array
    {
        return $this->transcript;
    }

    public function setTranscript(?array $transcript): static
    {
        $this->transcript = $transcript;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(?string $audio): static
    {
        $this->audio = $audio;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

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
            $task->setSourceMeeting($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getSourceMeeting() === $this) {
                $task->setSourceMeeting(null);
            }
        }

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
        }

        return $this;
    }

    public function removeProjectUser(ProjectUser $projectUser): static
    {
        $this->projectUsers->removeElement($projectUser);

        return $this;
    }

    public function getCraigId(): ?string
    {
        return $this->craigId;
    }

    public function setCraigId(?string $craigId): static
    {
        $this->craigId = $craigId;

        return $this;
    }

    public function getRecordedAt(): ?\DateTimeImmutable
    {
        return $this->recordedAt;
    }

    public function setRecordedAt(\DateTimeImmutable $recordedAt): static
    {
        $this->recordedAt = $recordedAt;

        return $this;
    }

    public function getCraigKey(): ?string
    {
        return $this->craigKey;
    }

    public function setCraigKey(?string $craigKey): static
    {
        $this->craigKey = $craigKey;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getActiveProcess(): ?Process
    {
        return $this->activeProcess;
    }

    public function setActiveProcess(?Process $activeProcess): static
    {
        $this->activeProcess = $activeProcess;

        return $this;
    }
}
