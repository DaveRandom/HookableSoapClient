<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

final class Response implements IResponse
{
    private $document;
    private $request;

    public function __construct(?\DOMDocument $document, Request $request)
    {
        $this->document = $document;
        $this->request = $request;
    }

    public function hasDocument(): bool
    {
        return $this->document !== null;
    }

    public function getDocument(): ?\DOMDocument
    {
        return $this->document;
    }

    public function setDocument(?\DOMDocument $document): void
    {
        $this->document = $document;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
