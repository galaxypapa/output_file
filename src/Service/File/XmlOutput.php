<?php

namespace App\Service\File;

use SimpleXMLElement;

class XmlOutput implements OutputMethodInterface
{
    const TYPE = 'xml';

    const XML_FILE = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><orders></orders>";

    private OrderPriceInfoInterface $orderPriceInfo;

    public function __construct(OrderPriceInfoInterface $orderPriceInfo)
    {
        $this->orderPriceInfo = $orderPriceInfo;
    }

    /**
     * Output as XML format file, however, this function hasn't been implemented yet
     *
     * @param array $data
     * @param $fileHandler
     */
    public function output(array $data, $fileHandler): void
    {
        $data = $this->prepareXmlLineData($data);
        $itemNode = $fileHandler->addChild('order_info');
        $itemNode->addChild('order_id', $data['order_id']);
        $itemNode->addChild('order_datetime', $data['order_datetime']);
        $itemNode->addChild('total_order_value', $data['total_order_value']);
        $itemNode->addChild('average_unit_price', $data['average_unit_price']);
        $itemNode->addChild('distinct_unit_count', $data['distinct_unit_count']);
        $itemNode->addChild('total_units_count', $data['total_units_count']);
        $itemNode->addChild('customer_state', $data['customer_state']);
    }


    /**
     * Prepare XML line data for output
     *
     * @param $data
     * @return array
     */
    private function prepareXmlLineData($data): array
    {
        $priceInfo = $this->orderPriceInfo->getOrderPriceInfo($data);

        return [
            'order_id' => trim($data['order_id']),
            'order_datetime' => trim($data['order_date']),
            'total_order_value' => $priceInfo['totalOrderValue'],
            'average_unit_price' => $priceInfo['averageUnitPrice'],
            'distinct_unit_count' => count($data['items']),
            'total_units_count' => $priceInfo['totalUnitsCount'],
            'customer_state' => trim($data['customer']['shipping_address']['state'])
        ];
    }

    /**
     * Restore the default properties setting
     */
    function restore(): void
    {
        // TODO: Implement restore() method.
    }


    /**
     * Create SimpleXMLElement object
     *
     * @param string $outputFile
     * @return mixed
     */
    function createHandler(string $outputFile): mixed
    {
        return new SimpleXMLElement(self::XML_FILE);
    }

    /**
     * Hook for finalise the job.
     *
     * @param $fileHandler
     * @param string $outputFile
     */
    public function finalize($fileHandler, string $outputFile): void
    {
        $fileHandler->asXML($outputFile);
    }
}