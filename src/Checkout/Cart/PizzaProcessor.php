<?php

namespace Shyim\Pizza\Checkout\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\Price\AbsolutePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\AbsolutePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\PriceCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shyim\Pizza\Checkout\Pizza\PizzaEntity;

class PizzaProcessor implements CartProcessorInterface, CartDataCollectorInterface
{
    private const DATA_KEY = 'pizza';

    private AbsolutePriceCalculator $priceCalculator;
    private EntityRepositoryInterface $pizzaRepository;

    public function __construct(AbsolutePriceCalculator $priceCalculator, EntityRepositoryInterface $pizzaRepository)
    {
        $this->priceCalculator = $priceCalculator;
        $this->pizzaRepository = $pizzaRepository;
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original->getLineItems()->filterType(PizzaLineItemFactory::TYPE);

        if ($lineItems->count() === 0) {
            return;
        }

        $ids = array_unique(array_filter($lineItems->getReferenceIds()));

        if (count($ids) === 0) {
            return;
        }

        $collectIds = [];

        foreach ($lineItems->getElements() as $lineItem) {
            if ($data->has(self::DATA_KEY . $lineItem->getReferencedId())) {
                continue;
            }

            $collectIds[] = $lineItem->getReferencedId();
        }

        if (count($collectIds) === 0) {
            return;
        }

        $pizza = $this->pizzaRepository->search(new Criteria($collectIds), $context->getContext());

        /** @var PizzaEntity $p */
        foreach ($pizza->getElements() as $p) {
            $data->set(self::DATA_KEY . $p->getId(), $p);
        }
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original->getLineItems()->filterType(PizzaLineItemFactory::TYPE);

        if ($lineItems->count() === 0) {
            return;
        }

        foreach ($lineItems->getElements() as $lineItem) {
            /** @var PizzaEntity $pizza */
            $pizza = $data->get(self::DATA_KEY . $lineItem->getReferencedId());

            $lineItem->setLabel($pizza->getName());
            $lineItem->setPriceDefinition(new AbsolutePriceDefinition($pizza->getPrice()));
            $lineItem->setPrice($this->priceCalculator->calculate($pizza->getPrice(), new PriceCollection([]), $context));

            $toCalculate->add($lineItem);
        }
    }
}
