<?php declare(strict_types=1);

use DaveRandom\HookableSoapClient\Request;
use DaveRandom\HookableSoapClient\SoapClient;

class AttributeAddingSoapClient extends SoapClient
{
    /**
     * Adds a whatever="somevalue" attribute to all <baz> elements with a qux="stuff" attributes that are direct
     * children of a <foo> element in the https://foo.com/bar/baz namespace.
     */
    protected function onBeforeRequest(Request $request): void
    {
        $xpath = new \DOMXPath($request->getDocument());
        $xpath->registerNamespace('foo', 'https://foo.com/bar/baz');

        /** @var \DOMElement $element */
        foreach ($xpath->query('//foo:bar/foo:baz[@qux="stuff"]') as $element) {
            $element->setAttribute('whatever', 'somevalue');
        }
    }
}
