<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

final class Response
{
    private $document;
    private $request;

    public function __construct(?\DOMDocument $document, Request $request)
    {
        $this->document = $document;
        $this->request = $request;
    }

    /**
     * Determine whether the response has a defined (not null) XML document.
     */
    public function hasDocument(): bool
    {
        return $this->document !== null;
    }

    /**
     * Get the response XML document.
     */
    public function getDocument(): ?\DOMDocument
    {
        return $this->document;
    }

    /**
     * Set the response XML document.
     */
    public function setDocument(?\DOMDocument $document): void
    {
        $this->document = $document;
    }

    /**
     * Get the Request object associated with the response.
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
