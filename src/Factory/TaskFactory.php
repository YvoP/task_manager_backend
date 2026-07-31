<?php

namespace App\Factory;

use App\Entity\Task;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Task>
 */
final class TaskFactory extends PersistentObjectFactory
{
    private const TITLES = [
        'Créer la page de connexion',
        'Configurer OAuth',
        'Ajouter les rôles utilisateurs',
        'Créer le tableau de bord',
        'Corriger le bug de pagination',
        'Optimiser les requêtes SQL',
        'Ajouter les tests unitaires',
        'Mettre en place Docker',
        'Déployer sur la préproduction',
        'Documenter l\'API',
        'Créer les notifications',
        'Créer la recherche globale',
        'Ajouter les logs d\'audit',
        'Importer les données historiques',
        'Créer la page Profil',
        'Ajouter le mode sombre',
        'Intégrer Stripe',
        'Refactoriser le service Meeting',
        'Corriger les permissions',
        'Créer le système de commentaires',
        'Ajouter les pièces jointes',
        'Optimiser le temps de chargement',
        'Créer la gestion des équipes',
        'Mettre à jour Symfony',
        'Corriger le responsive',
        'Créer les statistiques',
        'Ajouter les favoris',
        'Nettoyer les migrations',
        'Créer la gestion des invitations',
        'Finaliser le sprint',
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
        return Task::class;
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
            'createdAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-6 months')
            ),
        ];
    }

    public static function randomTitle(): string
    {
        return self::faker()->randomElement(self::TITLES);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }
}
