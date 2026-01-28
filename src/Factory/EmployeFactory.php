<?php

namespace App\Factory;

use App\Entity\Employe;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Employe>
 */
final class EmployeFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct() {}

    public static function class(): string
    {
        return Employe::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'prenom' => self::faker()->firstName(),
            'nom' => self::faker()->lastName(),
            'email' => self::faker()->unique()->safeEmail(),
            'dateEntree' => self::faker()->dateTimeBetween('-3 years', 'now'),
            'statut' => self::faker()->randomElement(['CDI', 'CDD', 'Freelance']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Employe $employe): void {})
        ;
    }
}
