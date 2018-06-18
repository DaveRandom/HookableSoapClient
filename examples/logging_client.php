<?php declare(strict_types=1);

use DaveRandom\HookableSoapClient\CallData;
use DaveRandom\HookableSoapClient\Request;
use DaveRandom\HookableSoapClient\Response;
use DaveRandom\HookableSoapClient\SoapClient;
use DaveRandom\HookableSoapClient\SoapHeaderArray;

interface Logger
{
    function log(string $message): void;
}

class LoggingSoapClient extends SoapClient
{
    private $logger;

    private function getFormattedXml(?\DOMDocument $doc): string
    {
        if ($doc === null) {
            return '';
        }

        $oldFormatOutput = $doc->formatOutput;
        $doc->formatOutput = true;

        $result = $doc->saveXML();

        $doc->formatOutput = $oldFormatOutput;

        return $result;
    }

    private function dumpVarToString($var): string
    {
        \ob_start();
        \var_dump($var);
        return \trim(\ob_get_clean());
    }

    private function formatCallData(CallData $callData): string
    {
        return <<<MESSAGE
 Method: {$callData->getFunctionName()}
 Args: {$this->dumpVarToString($callData->getArguments())}
 Options: {$this->dumpVarToString($callData->getOptions())}
 Headers: {$this->dumpVarToString($callData->getInputHeaders())}
MESSAGE;
    }

    private function formatRequest(Request $request): string
    {
        $uri = $request->hasUri() ? $request->getUri() : 'not defined';
        $action = $request->hasAction() ? $request->getAction() : 'not defined';

        return <<<MESSAGE
 URI: {$uri}
 Action: {$action}
 Version: {$request->getVersion()}
 Response Expected: {$this->dumpVarToString($request->isResponseExpected())}
 XML:
{$this->getFormattedXml($request->getDocument())}
MESSAGE;
    }

    public function __construct(Logger $logger, $wsdl, array $options = null)
    {
        parent::__construct($wsdl, $options);
        $this->logger = $logger;
    }

    protected function onBeforeCall(CallData $callData): void
    {
        $this->logger->log(__METHOD__ . "()\n{$this->formatCallData($callData)}");

        parent::onBeforeCall($callData);
    }

    protected function onBeforeRequest(Request $request): void
    {
        $this->logger->log(__METHOD__ . "()\n{$this->formatRequest($request)}");

        parent::onBeforeRequest($request);
    }

    protected function onResponse(Response $response): void
    {
        $this->logger->log(__METHOD__ . "()\n{$this->getFormattedXml($response->getDocument())}");

        parent::onResponse($response);
    }

    protected function onAfterCall($result, Response $response, SoapHeaderArray $responseHeaders)
    {
        $this->logger->log(__METHOD__ . "()\n Result: {$this->dumpVarToString($result)}\n Headers: {$this->dumpVarToString($responseHeaders)}");

        return parent::onAfterCall($result, $response, $responseHeaders);
    }
}
