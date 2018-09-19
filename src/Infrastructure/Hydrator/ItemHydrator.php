<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Aggregate\Item;
use Ramsey\Uuid\Uuid;

/**
 * Class ItemHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ItemHydrator extends BaseHydrator
{
    public function hydrate(array $data, $classNameOrObject)
    {
        $data['id'] = Uuid::fromString($data['id']);
        $data['price'] = new \Money\Money(
            $data['price']['amount'],
            new \Money\Currency($data['price']['currencyCode'])
        );

        return parent::hydrate($data, $classNameOrObject);
    }

    /**
     * Extract values from an object
     *
     * @param  Item $object
     *
     * @return array
     */
    public function extract($object)
    {
        return [
            'id'    => $object->id()->toString(),
            'name'  => $object->name(),
            'price' => [
                'amount'       => $object->price()->getAmount(),
                'currencyCode' => $object->price()->getCurrency(),
            ],
        ];
    }
}

