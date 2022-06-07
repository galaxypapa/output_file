<?php

namespace App\Service\File;


class OrderPriceInfo implements OrderPriceInfoInterface
{
    /**
     * Fetch the order price summary information
     *
     * @param array $data
     * @return array
     */
    public function getOrderPriceInfo(array $data): array
    {
        $totalOrderValue = 0;
        $averageUnitPrice = 0;
        $totalUnitsCount = 0;
        $totalUnitPrice = 0;
        $totalDiscountPrice = 0;
        $items = $data['items'] ?? [];
        foreach ($items as $item) {
            $totalUnitsCount += $this->getQuantity($item);
        }
        foreach ($items as $item) {
            $totalUnitPrice += (string)($this->getQuantity($item) * $this->getUnitPrice($item));
        }
        $averageUnitPrice = round($totalUnitPrice / $totalUnitsCount, 2);

        foreach ($data['discounts'] ?? [] as $item) {
            $totalDiscountPrice += $this->getValue($item);
        }
        $totalOrderValue = (string)($totalUnitPrice - $totalDiscountPrice);
        return [
            'totalOrderValue' => $totalOrderValue,
            'averageUnitPrice' => $averageUnitPrice,
            'totalUnitsCount' => $totalUnitsCount
        ];
    }


    /**
     * Get item quantity
     *
     * @param mixed $item
     * @return int
     */
    private function getQuantity(mixed $item): int
    {
        return (isset($item['quantity']) && is_int($item['quantity']) && $item['quantity'] > 0)
            ? $item['quantity'] : 0;
    }


    /**
     * Get unit price
     *
     * @param mixed $item
     * @return int|float
     */
    private function getUnitPrice(mixed $item): int|float
    {
        return (isset($item['unit_price']) && is_numeric($item['unit_price']) && $item['unit_price'] > 0)
            ? $item['unit_price'] : 0;
    }

    /**
     * Get item discount value
     *
     * @param mixed $item
     * @return int|float
     */
    private function getValue(mixed $item): int|float
    {
        return (isset($item['value']) && is_numeric($item['value']) && $item['value'] > 0)
            ? $item['value'] : 0;
    }

}