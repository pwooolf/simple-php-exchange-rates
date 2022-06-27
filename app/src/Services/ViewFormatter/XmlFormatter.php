<?php

namespace App\Services\ViewFormatter;

use DOMDocument;
use SimpleXMLElement;

class XmlFormatter extends AbstractFormatter
{
    private const CONTENT_TYPE = 'text/xml';

    public function show()
    {
        $xml = new SimpleXMLElement('<ExchangeRates/>');
        $this->array_to_xml($this->data, $xml);

        $domxml = new DOMDocument('1.0');
        $domxml->preserveWhiteSpace = false;
        $domxml->formatOutput = true;
        $domxml->loadXML($xml->asXML());

        header(sprintf('Content-type: %s; charset=utf-8', self::CONTENT_TYPE));
        echo $domxml->saveXML();
    }

    private function array_to_xml($array, &$xml)
    {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml->addChild($key);
                    $this->array_to_xml($value, $subnode);
                } else {
                    $this->array_to_xml($value, $subnode);
                }
            } else {
                $xml->addChild($key, $value);
            }
        }
    }
}