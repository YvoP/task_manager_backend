<?php

namespace App\Dto;

final class TranscriptionInputDto
{
    /**
     * @var array<int, array{
     *     user: string,
     *     start: float,
     *     end: float,
     *     text: string
     * }>
     */
    public array $transcript = [];
}
