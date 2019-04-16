<?php

function xmlParsing($filePath, $nodeName)
{
    $xmlReader = new XMLReader();
    if (!$xmlReader->open($filePath)) {
        return false;
    }
    
    while ($xmlReader->read() && $xmlReader->name !== $nodeName) {
    }

    $dom = new DOMDocument();

    while ($xmlReader->name === $nodeName) {
        $element = simplexml_import_dom($xmlReader->expand($dom));
        yield $element;

        $xmlReader->next($nodeName);
    }

    return $xmlReader->close();
}

$filePath = '';
$nodeName = '';

foreach (xmlParsing($filePath, $nodeName) as $index => $node) {
    // node processing
}
