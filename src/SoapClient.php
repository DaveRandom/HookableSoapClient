<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

use Room11\DOMUtils\LibXMLFatalErrorException;

abstract class SoapClient extends \SoapClient
{
    private $currentCallData;
    private $currentResponse;

    /**
     * @param \DOMDocument|string|null $xml
     * @throws LibXMLFatalErrorException
     */
    private function formatResponseXmlAsDocument($xml): ?\DOMDocument
    {
        if ($xml === null || $xml instanceof \DOMDocument) {
            return $xml;
        }

        if (!\is_string($xml)) {
            throw new \TypeError('Response XML must be a string or an instance of ' . \DOMDocument::class);
        }

        return \trim($xml) !== ''
            ? \Room11\DOMUtils\domdocument_load_xml($xml)
            : null;
    }

    public function __doRequest($xml, $uri, $action, $version, $oneWay = 0): string
    {
        try {
            $requestDocument = \Room11\DOMUtils\domdocument_load_xml($xml);
        } catch (LibXMLFatalErrorException $e) {
            throw new \Error("Failed to parse request XML: {$e->getMessage()}", $e->getCode(), $e);
        }

        $request = new Request($requestDocument, $uri, $action, $version, !$oneWay, $this->currentCallData);

        $this->onBeforeRequest($request);

        $responseXml = $this->onRequest($request);

        try {
            $responseDocument = $this->formatResponseXmlAsDocument($responseXml);
        } catch (LibXMLFatalErrorException $e) {
            return $this->onResponseParseFailed($responseXml, $request, $e->getLibXMLError()) ?? '';
        }

        $this->currentResponse = new Response($responseDocument, $request);
        $this->onResponse($this->currentResponse);

        return $this->currentResponse->hasDocument()
            ? $this->currentResponse->getDocument()->saveXML()
            : '';
    }

    public function __soapCall($functionName, $arguments = [], $options = [], $inputHeaders = [], &$outputHeaders = [])
    {
        try {
            $this->currentCallData = new CallData(
                $functionName,
                new CallArguments($arguments),
                CallOptions::createFromArray($options ?? []),
                new SoapHeaderArray($inputHeaders)
            );

            $this->onBeforeCall($this->currentCallData);

            $result = parent::__soapCall(
                $this->currentCallData->getFunctionName(),
                $this->currentCallData->getArguments()->getArrayCopy(),
                $this->currentCallData->getOptions()->toArray(),
                $this->currentCallData->getInputHeaders()->getArrayCopy(),
                $outputHeaders
            );

            $outputHeaders = new SoapHeaderArray($outputHeaders);
            $result = $this->onAfterCall($result, $this->currentResponse, $outputHeaders);
            $outputHeaders = $outputHeaders->getArrayCopy();

            return $result;
        } finally {
            $this->currentCallData = null;
            $this->currentResponse = null;
        }
    }

    public function __call($functionName, $arguments)
    {
        return $this->__soapCall($functionName, $arguments);
    }

    public function __construct($wsdl, array $options = null)
    {
        parent::__construct($wsdl, $options ?? []);
    }

    /**
     * When overridden in a child class, this method is called before a request is generated from the arguments used
     * to invoke the SOAP call. Modifications to the passed Call object will be reflected in the request that is
     * generated.
     */
    protected function onBeforeCall(CallData $callData): void { }

    /**
     * When overridden in a child class, this method is called before a request is sent to the remote server.
     * Modifications to the passed Request object will be reflected in the request that is sent.
     */
    protected function onBeforeRequest(Request $request): void { }

    /**
     * Send the request to the server and retrieve the response. Can be overridden in a child class to implement a
     * custom transport mechanism. Must return a string containing an XML document, or null if the request does not
     * generate a response.
     *
     * @return string|\DOMDocument|null
     */
    protected function onRequest(Request $request)
    {
        return parent::__doRequest(
            $request->getDocument()->saveXML(),
            $request->getUri(),
            $request->getAction(),
            $request->getVersion(),
            (int)!$request->isResponseExpected()
        );
    }

    /**
     * When overridden in a child class, this method is called after the response is recieved, and before the data
     * is processed by the SOAP extension. Modifications to the passed Response object will be reflected in the
     * generation of the return value of the underlying SOAP call.
     */
    protected function onResponse(Response $response): void { }

    /**
     * When overridden in a child class, this method is called after the result data is generated from the response.
     * Must return the result data that should be returned to the caller.
     *
     * @param mixed $result
     * @return mixed
     */
    protected function onAfterCall(
        $result,
        /** @noinspection PhpUnusedParameterInspection */ Response $response,
        /** @noinspection PhpUnusedParameterInspection */ SoapHeaderArray $responseHeaders
    ) {
        return $result;
    }

    /**
     * When overridden in a child class, this method is called when the response recieved from the server could not
     * be parsed as an XML document. The return value will be processed by the SOAP extension as if it were the
     * data returned by the server
     */
    protected function onResponseParseFailed(
        string $responseData,
        /** @noinspection PhpUnusedParameterInspection */  Request $request,
        /** @noinspection PhpUnusedParameterInspection */  \LibXMLError $error
    ): ?string
    {
        return $responseData;
    }
}
