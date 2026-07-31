<?php

namespace App\Factory;

use App\Entity\Meeting;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Meeting>
 */
final class MeetingFactory extends PersistentObjectFactory
{
    private const NAMES = [
        'Daily',
        'Sprint Planning',
        'Sprint Review',
        'Rétrospective',
        'Point Client',
        'Réunion Architecture',
        'Comité Projet',
        'Atelier UX',
    ];

    private const SUMMARIES = [
        'Les objectifs du sprint ont été validés.',
        'Deux blocages techniques ont été identifiés.',
        'Le client demande quelques ajustements.',
        'Le déploiement est prévu vendredi.',
        'Les développements avancent conformément au planning.',
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
        return Meeting::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]

    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->randomElement(self::NAMES),
            'summary' => self::faker()->randomElement(self::SUMMARIES),
            'transcript' => self::faker()->paragraphs(12, true),
            'duration' => self::faker()->numberBetween(15, 120),
            'audio' => null,
            'createdAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-6 months')
            ),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this// ->afterInstantiate(function(Meeting $meeting): void {})
            ;
    }
}
