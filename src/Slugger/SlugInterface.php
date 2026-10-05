<?php

namespace App\Slugger;

interface SlugInterface
{

    function setSlug(string $field);

    function getFields(): string;

}
