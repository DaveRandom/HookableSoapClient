<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

final class CallOptions
{
    private $location;
    private $targetNamespaceUri;
    private $action;

    public static function createFromArray(array $options): self
    {
        $result = new self();

        $result->location = $options['location'] ?? null;
        $result->targetNamespaceUri = $options['uri'] ?? null;
        $result->action = $options['soapaction'] ?? null;

        return $result;
    }

    public function __construct(?string $location = null, ?string $targetNamespaceUri = null, ?string $action = null)
    {
        $this->location = $location;
        $this->targetNamespaceUri = $targetNamespaceUri;
        $this->action = $action;
    }

    /**
     * Get the target URI for the request.
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Set the target URI for the request.
     */
    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Get the target namespace URI for the XML document generated for the request.
     */
    public function getTargetNamespaceUri(): ?string
    {
        return $this->targetNamespaceUri;
    }

    /**
     * Set the target namespace URI for the XML document generated for the request.
     */
    public function setTargetNamespaceUri(?string $targetNamespaceUri): self
    {
        $this->targetNamespaceUri = $targetNamespaceUri;
        return $this;
    }

    /**
     * Get the SOAP action for the request.
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Set the SOAP action for the request.
     */
    public function setAction(?string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->location !== null) {
            $result['location'] = $this->location;
        }

        if ($this->targetNamespaceUri !== null) {
            $result['uri'] = $this->targetNamespaceUri;
        }

        if ($this->action !== null) {
            $result['soapaction'] = $this->action;
        }

        return $result;
    }
}
