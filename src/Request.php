<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

final class Request implements IRequest
{
    private $document;
    private $uri;
    private $action;
    private $version;
    private $responseExpected;

    private $responseDocument;
    private $responseOverridden = false;

    public function __construct(\DOMDocument $document, ?string $uri, ?string $action, int $version, bool $responseExpected)
    {
        $this->document = $document;
        $this->uri = $uri;
        $this->action = $action;
        $this->version = $version;
        $this->responseExpected = $responseExpected;
    }

    public function getDocument(): \DOMDocument
    {
        return $this->document;
    }

    /**
     * @return $this
     */
    public function setDocument(\DOMDocument $document): self
    {
        $this->document = $document;
        return $this;
    }

    public function hasUri(): bool
    {
        return $this->uri !== null;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function hasAction(): bool
    {
        return $this->action !== null;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @return $this
     */
    public function setAction(?string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return $this
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function isResponseExpected(): bool
    {
        return $this->responseExpected;
    }

    public function getResponseDocument(): ?\DOMDocument
    {
        return $this->responseDocument;
    }

    /**
     * @return $this
     */
    public function setResponseDocument(?\DOMDocument $responseDocument): self
    {
        $this->responseOverridden = true;
        $this->responseDocument = $responseDocument;
        return $this;
    }

    public function removeResponseDocument(): void
    {
        $this->responseDocument = null;
        $this->responseOverridden = false;
    }

    public function isResponseOverridden(): bool
    {
        return $this->responseOverridden;
    }

}
