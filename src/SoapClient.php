<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

use Room11\DOMUtils\LibXMLFatalErrorException;

abstract class SoapClient extends \SoapClient implements ISoapClient
{
    private $haveBeforeRequestHandler;
    private $haveResponseHandler;
    private $haveResponseParseFailureHandler;

    private static function parseXmlString(string $xml): \DOMDocument
    {
        try {
            return \Room11\DOMUtils\domdocument_load_xml($xml);
        } catch (LibXMLFatalErrorException $e) {
            throw new XmlParseFailedError("Failed to parse XML data: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    private static function isMethodOverridden(callable $method): bool
    {
        try {
            return (new \ReflectionMethod($method[0], $method[1]))->getDeclaringClass()->getName() !== self::class;
        } catch (\ReflectionException $e) {
            throw new \Error("Failed to get details of method: {$method[1]}");
        }
    }

    public function __doRequest($xml, $uri, $action, $version, $oneWay = 0): string
    {
        $request = new Request(self::parseXmlString((string)$xml), $uri, $action, $version, !$oneWay);

        if ($this->haveBeforeRequestHandler) {
            $this->onBeforeRequest($request);
        }

        $responseXml = parent::__doRequest(
            $request->getDocument()->saveXML(),
            $request->getUri(),
            $request->getAction(),
            $request->getVersion(),
            $oneWay
        );

        if (!$this->haveResponseHandler) {
            return $responseXml;
        }

        try {
            $response = new Response($responseXml !== '' ? self::parseXmlString($responseXml) : null, $request);
            $this->onResponse($response);

            $responseXml = $response->hasDocument()
                ? $response->getDocument()->saveXML()
                : '';
        } catch (XmlParseFailedError $e) {
            if ($this->haveResponseParseFailureHandler) {
                $responseXml = $this->onResponseParseFailed($responseXml, $request);
            }
        }

        return $responseXml;
    }

    public function __construct($wsdl, array $options = null)
    {
        parent::__construct($wsdl, $options ?? []);

        $this->haveBeforeRequestHandler = self::isMethodOverridden([$this, 'onBeforeRequest']);
        $this->haveResponseHandler = self::isMethodOverridden([$this, 'onResponse']);
        $this->haveResponseParseFailureHandler = self::isMethodOverridden([$this, 'onResponseParseFailed']);
    }

    /**
     * When overridden in a child class, this method is called before a request is sent to the remote server.
     * Modifications to the passed Request object will be reflected in the request that is sent.
     */
    public function onBeforeRequest(Request $request): void { }

    /**
     * When overridden in a child class, this method is called after the response is recieved, and before the data
     * is processed by the SOAP extension. Modifications to the passed Response object will be reflected in the
     * generation of the return value of the underlying SOAP call.
     */
    public function onResponse(Response $response): void { }

    /**
     * When overridden in a child class, this method is called when the response recieved from the server could not
     * be parsed as an XML document. The return value will be processed by the SOAP extension as if it were the
     * data returned by the server
     */
    public function onResponseParseFailed(string $responseData, Request $request): string { }
}
