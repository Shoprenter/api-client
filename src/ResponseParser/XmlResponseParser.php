<?php

namespace ShopRenter\ResponseParser;

use SimpleXMLElement;

class XmlResponseParser extends AbstractResponseParser implements ResponseParserInterface
{
    /**
     * @return array
     */
    public function parse()
    {
        $xml = simplexml_load_string(
            $this->response,
            null,
            LIBXML_NOCDATA
        );

        return $this->xmlToArray($xml);
    }

    /**
     * @param SimpleXMLElement|string $xml
     * @return array
     */
    protected function xmlToArray($xml)
    {
        if (is_string($xml)) {
            return $xml;
        }

        $children = (array) $xml->children();
        $data = [];

        if (count($children) > 0) {
            foreach ($children as $key => $child) {
                if (is_array($child) && count($child) > 0 && !$this->isAssociative($child)) {
                    foreach ($child as $cKey => $c) {
                        $data[$key][$cKey] = $this->xmlToArray($c);
                    }
                } else {
                    $data[$key] = $this->xmlToArray($child);
                }
            }
            return $data;
        } else {
            return (string) $xml;
        }
    }

    /**
     * Megvizsgálja, hogy a kapott tömb asszociatív-e,
     * tehát az értékeknek vannak-e kulcsok megadva
     *
     * @param $node
     * @return bool
     */
    protected function isAssociative($node)
    {
        return array_keys($node) !== range(0, count($node) - 1);
    }
}
