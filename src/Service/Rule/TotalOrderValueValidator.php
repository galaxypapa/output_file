<?php

namespace App\Service\Rule;

use App\Service\File\OrderPriceInfoInterface;

class TotalOrderValueValidator implements ValidatorInterface
{
    const NAME = 'totalOrderValue';

    private OrderPriceInfoInterface $orderPriceInfo;

    public function __construct(OrderPriceInfoInterface $orderPriceInfo)
    {
        $this->orderPriceInfo = $orderPriceInfo;
    }

    /**
     * Validate total order value, if total order value equal 0 then return false
     *
     * @param string $data
     * @return bool
     */
    public function validate(string $data): bool
    {
        $priceInfo = $this->orderPriceInfo->getOrderPriceInfo(json_decode($data, true));
        if ($priceInfo['totalOrderValue'] === 0)
            return false;
        return true;
    }
}