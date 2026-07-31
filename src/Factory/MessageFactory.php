<?php

namespace App\Factory;

use App\Entity\Message;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Message>
 */
final class MessageFactory extends PersistentObjectFactory
{
    private const MESSAGES = [
        "J'ai terminé la PR.",
        "Je peux faire la review cet après-midi.",
        "Le client vient de répondre.",
        "Attention, il reste un conflit Doctrine.",
        "Je m'occupe du ticket.",
        "La CI est verte.",
        "Quelqu'un peut tester sur Safari ?",
        "La maquette est validée.",
        "Je déploie en préproduction.",
        "Je pousse les derniers commits.",
        "Le bug est reproduit.",
        "On en parle pendant le daily ?",
        "La réunion est déplacée à 14h.",
        "J'ai ajouté les tests.",
        "Ça fonctionne chez moi.",
        "Merci pour la review !",
        "Je prends cette tâche.",
        "Le client souhaite une modification.",
        "On peut fusionner la branche.",
        "Je regarde ça demain matin.",
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
        return Message::class;
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
            'content' => self::faker()->randomElement(self::MESSAGES),
            'createdAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-3 months')
            ),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Message $message): void {})
        ;
    }
}
