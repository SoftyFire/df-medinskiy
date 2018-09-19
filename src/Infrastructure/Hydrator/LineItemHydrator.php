<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Aggregate\Item;
use Billing\Domain\Entity\LineItem;
use Ramsey\Uuid\Uuid;

/**
 * Class LineItemHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class LineItemHydrator extends BaseHydrator
{
    /**
     * @var ItemHydrator
     */
    private $itemHydrator;

    public function __construct(ItemHydrator $itemHydrator)
    {
        $this->itemHydrator = $itemHydrator;
    }

    public function hydrate(array $data, $classNameOrObject)
    {
        $data['id'] = Uuid::fromString($data['id']);
        $data['item'] = $this->itemHydrator->hydrate($data['item'], Item::class);
        $data['quantity'] = (int) $data['quantity'];

        return parent::hydrate($data, $classNameOrObject);
    }

    /**
     * Extract values from an object
     *
     * @param  LineItem $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'id'       => $object->id()->toString(),
            'item'     => $this->itemHydrator->extract($object->item()),
            'quantity' => $object->quantity(),
        ];
    }
}
