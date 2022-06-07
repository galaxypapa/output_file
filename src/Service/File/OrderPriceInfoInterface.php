<?php

namespace App\Service\File;

interface OrderPriceInfoInterface
{
    /**
     * Fetch the order price summary information
     *
     * @param array $data
     * @return array
     */
    function getOrderPriceInfo(array $data): array;
}