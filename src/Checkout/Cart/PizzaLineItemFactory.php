<?php

namespace Shyim\Pizza\Checkout\Cart;

use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItemFactoryHandler\LineItemFactoryInterface;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class PizzaLineItemFactory implements LineItemFactoryInterface
{
    public const TYPE = 'pizza';

    public function supports(string $type): bool
    {
        return $type === self::TYPE;
    }

    public function create(array $data, SalesChannelContext $context): LineItem
    {
        $pizzaId = $data['pizzaId'] ?? null;

        if ($pizzaId === null) {
            throw new \InvalidArgumentException('Please select an pizzaId first');
        }

        $item = new LineItem($data['id'] ?? Uuid::randomHex(), self::TYPE, $pizzaId);
        $this->update($item, $data, $context);

        return $item;
    }

    public function update(LineItem $lineItem, array $data, SalesChannelContext $context): void
    {
    }
}
