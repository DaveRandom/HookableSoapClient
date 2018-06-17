# Hookable SOAP Client

A thin layer for `SoapClient` exposing a cleaner API for manipulating the request/response XML documents for SOAP calls. 

## API

### [SoapClient](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/SoapClient.php)

```php
/**
 * Extend this class and override the require method(s), the use as a drop-in replacement for SoapClient
 */
abstract class \DaveRandom\HookableSoapClient\SoapClient extends \SoapClient
{
    /**
     * When overridden in a child class, this method is called before a request is sent to the remote server.
     * Modifications to the passed Request object will be reflected in the request that is sent.
     */
    protected function onBeforeRequest(Request $request): void;

    /**
     * When overridden in a child class, this method is called after the response is recieved, and before the data
     * is processed by the SOAP extension. Modifications to the passed Response object will be reflected in the
     * generation of the return value of the underlying SOAP call.
     */
    protected function onResponse(Response $request): void;

    /**
     * When overridden in a child class, this method is called when the response recieved from the server could not
     * be parsed as an XML document. The return value will be processed by the SOAP extension as if it were the
     * data returned by the server
     */
    protected function onResponseParseFailed(string $responseData, Request $request): string;
}
```

### [Request](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/Request.php)

```php
final class \DaveRandom\HookableSoapClient\Request
{
    public function getDocument(): \DOMDocument;

    public function setDocument(\DOMDocument $document): self;

    public function hasUri(): bool;

    public function getUri(): ?string;

    public function setUri(?string $uri): self;

    public function hasAction(): bool;

    public function getAction(): ?string;

    public function setAction(?string $action): self;

    public function getVersion(): int;

    public function setVersion(int $version):self;

    public function isResponseExpected(): bool;

    public function getResponseDocument(): ?\DOMDocument;

    public function setResponseDocument(?\DOMDocument $document): self;

    public function removeResponseDocument(): void;

    public function isResponseOverridden(): bool;
}
```

#### [Response](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/Response.php)

```php
final class \DaveRandom\HookableSoapClient\Response
{
    public function hasDocument(): bool;
            
    public function getDocument(): ?\DOMDocument;
            
    public function setDocument(?\DOMDocument $document): void;
            
    public function getRequest(): Request;
}
```
