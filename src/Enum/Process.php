<?php

namespace App\Enum;

enum Process: string
{
    case TRANSCRIBING = 'transcribing';
    case EXTRACTING_TASKS = 'extracting_tasks';
}
