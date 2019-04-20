<?php

/**
 * @param string       $filePath
 * @param string|array $nodeName
 *
 * @return Generator
 * @throws Exception
 */
function xmlParsing($filePath, $nodeName)
{
    $nodesNames = !is_array($nodeName) ? [$nodeName] : $nodeName;

    foreach ($nodesNames as $nodeName) {
        $xmlReader = new XMLReader();
        if (!$xmlReader->open($filePath)) {
            throw new \Exception('An error occurred during xml reading');
        }

        while ($xmlReader->read() && $xmlReader->name !== $nodeName) {
        }

        $dom = new DOMDocument();

        while ($xmlReader->name === $nodeName) {
            $element = simplexml_import_dom($xmlReader->expand($dom));
            yield $element;

            $xmlReader->next($nodeName);
        }

        $xmlReader->close();
    }
}

$filePath = '';
$nodeName = '';

foreach (xmlParsing($filePath, $nodeName) as $index => $node) {
    /**
     * @var SimpleXMLElement $node
     */
}
