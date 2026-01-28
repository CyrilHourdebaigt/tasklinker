<?php

namespace App\Factory;

use App\Entity\Tache;
use App\Factory\ProjetFactory;
use App\Factory\EmployeFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Tache>
 */
final class TacheFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct() {}

    public static function class(): string
    {
        return Tache::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'titre' => self::faker()->sentence(4),
            'description' => self::faker()->boolean(60) ? self::faker()->paragraph() : null,
            'dateLimite' => self::faker()->boolean(60) ? self::faker()->dateTimeBetween('now', '+2 months') : null,
            'statut' => self::faker()->randomElement(['To Do', 'Doing', 'Done']),
            'projet' => ProjetFactory::new(),
            'employe' => self::faker()->boolean(70) ? EmployeFactory::new() : null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Tache $tache): void {})
        ;
    }
}
