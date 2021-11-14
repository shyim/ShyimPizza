<?php

namespace Shyim\Pizza\Checkout\Pizza;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void             add(PizzaEntity $entity)
 * @method void             set(string $key, PizzaEntity $entity)
 * @method PizzaEntity[]    getIterator()
 * @method PizzaEntity[]    getElements()
 * @method PizzaEntity|null get(string $key)
 * @method PizzaEntity|null first()
 * @method PizzaEntity|null last()
 */
class PizzaCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return PizzaEntity::class;
    }
}
