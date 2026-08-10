<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateTaskDto
{

    public ?string $title = null;

    public ?string $description = null;

    #[Assert\Choice(choices: ['TODO', 'IN_PROGRESS', 'DONE'])]
    public ?string $status = null;

    public ?int $priority = null;

    public ?\DateTimeInterface $startDate = null;

    public ?\DateTimeInterface $deadline = null;

    public ?bool $isArchived = null;

    /**
     * I would pass the AddProjectUserProcessor id instead of the object.
     */
    public ?int $attributedTo = null;

    /**
     * Same for files.
     *
     * @var int[]|null
     */
    public ?array $fileIds = null;
}
