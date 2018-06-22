<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

final class CallData
{
    private $functionName;
    private $arguments;
    private $options;
    private $inputHeaders;
    private $clientOptions;

    public function __construct(
        string $functionName,
        CallArguments $arguments,
        CallOptions $options,
        SoapHeaderArray $headers,
        array $clientOptions
    ) {
        $this->functionName = $functionName;
        $this->arguments = $arguments;
        $this->options = $options;
        $this->inputHeaders = $headers;
        $this->clientOptions = $clientOptions;
    }

    /**
     * Get the name of the SOAP function used to invoke the call
     */
    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    /**
     * Set the name of the SOAP function used to invoke the call
     *
     * @return $this for method chaining
     */
    public function setFunctionName(string $functionName): self
    {
        $this->functionName = $functionName;
        return $this;
    }

    /**
     * Get the arguments passed when invoking the call. Modifications will be reflected in the request XML document.
     */
    public function getArguments(): CallArguments
    {
        return $this->arguments;
    }

    /**
     * Get the options passed when invoking the call. Modifications will be reflected in the request XML document.
     */
    public function getOptions(): CallOptions
    {
        return $this->options;
    }

    /**
     * Get the SOAP headers passed when invoking the call. Modifications will be reflected in the request XML document.
     */
    public function getInputHeaders(): SoapHeaderArray
    {
        return $this->inputHeaders;
    }

    /**
     * Get the options the SoapClient was initialised with
     */
    public function getClientOptions(): array
    {
        return $this->clientOptions;
    }
}
