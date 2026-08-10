<?php

namespace App\Slugger;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TraitSlug
{
    #[ORM\Column(type: 'string')]
    #[Groups(['generated_menu_recipe'])]
    private ?string $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

}
