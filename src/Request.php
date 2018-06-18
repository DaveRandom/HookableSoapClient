<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

final class Request
{
    private $document;
    private $uri;
    private $action;
    private $version;
    private $responseExpected;
    private $callData;

    public function __construct(
        \DOMDocument $document,
        ?string $uri,
        ?string $action,
        int $version,
        bool $responseExpected,
        CallData $callData
    ) {
        $this->document = $document;
        $this->uri = $uri;
        $this->action = $action;
        $this->version = $version;
        $this->responseExpected = $responseExpected;
        $this->callData = $callData;
    }

    /**
     * Get the DOMDocument that represents the XML that will be sent in the body of the request
     */
    public function getDocument(): \DOMDocument
    {
        return $this->document;
    }

    /**
     * Set a new DOMDocument that represents the XML that will be sent in the body of the request
     *
     * @return $this for method chaining
     */
    public function setDocument(\DOMDocument $document): self
    {
        $this->document = $document;
        return $this;
    }

    /**
     * Determine whether the request has a defined (not null) target URI
     */
    public function hasUri(): bool
    {
        return $this->uri !== null;
    }

    /**
     * Get the target URI
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * Set the target URI
     *
     * @return $this for method chaining
     */
    public function setUri(?string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Determine whether the request has a defined (not null) SOAP action
     */
    public function hasAction(): bool
    {
        return $this->action !== null;
    }

    /**
     * Get the SOAP action
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Set the SOAP action
     *
     * @return $this for method chaining
     */
    public function setAction(?string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get the SOAP version
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Set the SOAP version
     *
     * @return $this for method chaining
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Determine whether the underlying SoapClient is expecting a non-empty response document
     */
    public function isResponseExpected(): bool
    {
        return $this->responseExpected;
    }

    /**
     * Get the CallData object associated with this request
     */
    public function getCallData(): CallData
    {
        return $this->callData;
    }
}
