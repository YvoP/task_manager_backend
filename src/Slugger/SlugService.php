<?php

namespace App\Slugger;

use Symfony\Component\String\Slugger\SluggerInterface;

readonly class SlugService
{

    public function __construct(private SluggerInterface $slugger) { }

    /**
     * Slugify a string
     *
     * @param string $value
     * @return string
     */
    function slugify(string $value) :string
    {
        return $this->slugger->slug($value)->lower();
    }
}
