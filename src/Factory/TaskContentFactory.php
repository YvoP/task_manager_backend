<?php

namespace App\Factory;

use App\Entity\TaskContent;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<TaskContent>
 */
final class TaskContentFactory extends PersistentObjectFactory
{
    private const TITLES = [
        'Créer la page de connexion',
        'Configurer OAuth',
        'Ajouter les rôles utilisateurs',
        'Créer le tableau de bord',
        'Corriger le bug de pagination',
        'Optimiser les requêtes SQL',
        'Ajouter les tests unitaires',
        'Créer les notifications',
        'Intégrer Stripe',
        'Documenter l\'API',
        'Créer le mode sombre',
        'Ajouter la recherche globale',
        'Refactoriser le service Meeting',
        'Mettre en place Docker',
        'Créer les statistiques',
        'Corriger les permissions',
        'Créer les pièces jointes',
        'Déployer la version bêta',
        'Créer la page Profil',
        'Optimiser les performances',
    ];

    private const DESCRIPTIONS = [
        'Cette fonctionnalité est demandée pour le prochain sprint.',
        'Suite aux retours du client, quelques ajustements sont nécessaires.',
        'Pensez à ajouter des tests unitaires avant la fusion.',
        'Vérifier la compatibilité avec Firefox et Safari.',
        'À faire après validation de la maquette Figma.',
        'Bloqué en attente de validation métier.',
        'Le développement est déjà commencé.',
        'Attention aux performances sur les gros volumes.',
        'Ne pas oublier la documentation.',
        'Le ticket dépend d\'une autre tâche.',
    ];

    private const STATUS = [
        'TODO',
        'IN_PROGRESS',
        'IN_REVIEW',
        'DONE',
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
        return TaskContent::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $updated = self::faker()->dateTimeBetween('-4 months');

        return [
            'updatedAt' => \DateTimeImmutable::createFromMutable($updated),

            'title' => self::faker()->randomElement(self::TITLES),

            'description' => self::faker()->optional(0.9)
                ->randomElement(self::DESCRIPTIONS),

            // AppFixtures may override this to create realistic history
            'status' => self::faker()->randomElement(self::STATUS),

            // 1 = low, 4 = critical
            'priority' => self::faker()->randomElement([
                2,
                2,
                2,
                3,
                3,
                1,
                4,
            ]),

            'startDate' => self::faker()->optional(0.8)
                ->dateTimeBetween('-4 months', '+1 week'),

            'deadline' => self::faker()->optional(0.7)
                ->dateTimeBetween('+2 days', '+2 months'),

            'isArchived' => false,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(TaskContent $taskContent): void {})
        ;
    }
}
