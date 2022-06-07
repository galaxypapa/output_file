<?php

namespace App\Service\File;

use SplFileObject;

class CsvOutput implements OutputMethodInterface
{
    const TYPE = 'csv';

    const SEPARATOR = ',';

    /**
     * To decide whether to create the header description in the CSV file or not.
     * True to create the header. It should read from the command's argument.
     *
     * @var bool
     */
    private bool $isCreatedHeader = true;

    private array $header = [
        'order_id', 'order_datetime', 'total_order_value',
        'average_unit_price', 'distinct_unit_count',
        'total_units_count', 'customer_state'
    ];

    private OrderPriceInfoInterface $orderPriceInfo;

    public function __construct(OrderPriceInfoInterface $orderPriceInfo)
    {
        $this->orderPriceInfo = $orderPriceInfo;
    }

    /**
     * Prepare CSV line data for output
     *
     * @param $data
     * @return array
     */
    private function prepareCsvLineData($data): array
    {
        $priceInfo = $this->orderPriceInfo->getOrderPriceInfo($data);

        return [
            trim($data['order_id']),
            trim($data['order_date']),
            $priceInfo['totalOrderValue'],
            $priceInfo['averageUnitPrice'],
            count($data['items']),
            $priceInfo['totalUnitsCount'],
            trim($data['customer']['shipping_address']['state'])
        ];
    }

    /**
     * Output the data into the csv file
     *
     * @param array $data
     * @param $fileHandler
     */
    public function output(array $data, $fileHandler): void
    {
        if ($this->isCreatedHeader === true)
            $fileHandler->fputcsv($this->header, self::SEPARATOR);
        $fileHandler->fputcsv($this->prepareCsvLineData($data), self::SEPARATOR);
        $this->isCreatedHeader = false;
    }

    /**
     * @param bool $isCreatedHeader
     */
    public function setIsCreatedHeader(bool $isCreatedHeader): void
    {
        $this->isCreatedHeader = $isCreatedHeader;
    }


    /**
     * Restore the default properties setting
     */
    public function restore(): void
    {
        $this->setIsCreatedHeader(true);
    }


    /**
     * create a SplFileObject
     *
     * @param string $outputFile
     * @return mixed
     */
    public function createHandler(string $outputFile): mixed
    {
        return new SplFileObject($outputFile, 'a');
    }

    /**
     * Hook for finalise the job. No need to implement in this class
     *
     * @param $fileHandler
     * @param string $outputFile
     */
    public function finalize($fileHandler, string $outputFile): void
    {
    }
}