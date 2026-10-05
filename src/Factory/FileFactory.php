<?php

namespace App\Factory;

use App\Entity\File;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<File>
 */
final class FileFactory extends PersistentObjectFactory
{
    private const FILES = [
        ['Compte-rendu Sprint.pdf', 'pdf'],
        ['Architecture.drawio', 'drawio'],
        ['Planning.xlsx', 'xlsx'],
        ['Maquette.fig', 'fig'],
        ['Documentation API.pdf', 'pdf'],
        ['Logo.svg', 'svg'],
        ['Spécifications.docx', 'docx'],
    ];

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return File::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $file = self::faker()->randomElement(self::FILES);

        return [
            'name' => $file[0],
            'format' => $file[1],
            'path' => '/uploads/'.$file[0],
            'fileSize' => self::faker()->numberBetween(15_000, 8_000_000),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(File $file): void {})
        ;
    }
}
