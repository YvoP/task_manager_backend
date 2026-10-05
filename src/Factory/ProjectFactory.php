<?php

namespace App\Factory;

use App\Entity\Project;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Project>
 */
final class ProjectFactory extends PersistentObjectFactory
{
    private const PROJECTS = [
        'Refonte du site vitrine',
        'Application Mobile',
        'CRM Commercial',
        'Intranet RH',
        'API Paiement',
        'Migration Cloud',
        'Portail Client',
        'Plateforme E-learning',
        'Tableau de bord BI',
        'Support Technique',
        'Assistant IA',
        'Infrastructure DevOps',
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
        return Project::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $name = self::faker()->randomElement(self::PROJECTS);

        return [
            'name' => $name,
            'discordServer' => self::faker()->optional()->slug(),
            'discordChannel' => self::faker()->optional()->slug(),
            'autoRecordDiscordMeeting' => self::faker()->boolean(35),
            'createdAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-2 years')
            ),
            'isArchived' => self::faker()->boolean(10),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Project $project): void {})
        ;
    }
}
