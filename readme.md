# Hookable SOAP Client

A thin layer for `SoapClient` exposing a cleaner API for manipulating the request/response XML documents for SOAP calls. 

## API

### [SoapClient](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/SoapClient.php)

```php
/**
 * Extend this class and override the require method(s), then use as a drop-in replacement for SoapClient
 */
abstract class \DaveRandom\HookableSoapClient\SoapClient extends \SoapClient
{
    /**
     * When overridden in a child class, this method is called before a request is generated from the arguments
     * used to invoke the SOAP call. Modifications to the passed Call object will be reflected in the request
     * that is generated.
     */
    protected void onBeforeCall(CallData $callData);

    /**
     * When overridden in a child class, this method is called before a request is sent to the remote server.
     * Modifications to the passed Request object will be reflected in the request that is sent.
     */
    protected void onBeforeRequest(Request $request);

    /**
     * Send the request to the server and retrieve the response. Can be overridden in a child class to implement
     * a custom transport mechanism. Must return an XML document, or null if the request does not generate a
     * response.
     */
    protected string|\DOMDocument|null onRequest(Request $request);

    /**
     * When overridden in a child class, this method is called after the response is recieved, and before the
     * data is processed by the SOAP extension. Modifications to the passed Response object will be reflected
     * in the generation of the return value of the underlying SOAP call.
     */
    protected void onResponse(Response $response);

    /**
     * When overridden in a child class, this method is called when the response recieved from the server could
     * not be parsed as an XML document. The return value will be processed by the SOAP extension as if it were
     * the data returned by the server.
     */
    protected ?string onResponseParseFailed(string $responseData, Request $request, \LibXMLError $error);

    /**
     * When overridden in a child class, this method is called after the result data is generated from the
     * response. Must return the result data that should be passed back to the caller.
     */
    protected mixed onAfterCall(mixed $result, Response $response, SoapHeaderArray $responseHeaders);
}
```

### [CallData](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/CallData.php)

```php
/**
 * Represents the data used to invoke SoapClient#__soapCall(), or an indirect call via a named service method
 * invocation.
 * Modifications made in SoapClient#onBeforeCall() will be reflected in the XML document generated for the
 * resulting SOAP request.
 */
final class \DaveRandom\HookableSoapClient\CallData
{
    /**
     * Get the name of the SOAP function used to invoke the call.
     */
    public string getFunctionName();

    /**
     * Set the name of the SOAP function used to invoke the call.
     */
    public $this setFunctionName(string $functionName);

    /**
     * Get the arguments passed when invoking the call.
     */
    public CallArguments getArguments();

    /**
     * Get the options passed when invoking the call.
     */
    public CallOptions getOptions();

    /**
     * Get the SOAP headers passed when invoking the call.
     */
    public SoapHeaderArray getInputHeaders();
}
```

`CallArguments` and `SoapHeaderArray` inherit from `\ArrayObject`, thus modifications can be made directly to
these objects.

### [CallOptions](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/CallOptions.php)

```php
/**
 * Represents the data passed to the $options argument of SoapClient#__soapCall().
 * Modifications made in SoapClient#onBeforeCall() will be reflected in the XML document generated for the
 * resulting SOAP request.
 */
final class \DaveRandom\HookableSoapClient\CallOptions
{
    /**
     * Get the target URI for the request.
     */
    public ?string getLocation();

    /**
     * Set the target URI for the request.
     */
    public $this setLocation(?string $location);

    /**
     * Get the target namespace URI for the XML document generated for the request.
     */
    public ?string getTargetNamespaceUri();

    /**
     * Set the target namespace URI for the XML document generated for the request.
     */
    public $this setTargetNamespaceUri(?string $targetNamespaceUri);

    /**
     * Get the SOAP action for the request.
     */
    public ?string getAction();

    /**
     * Set the SOAP action for the request.
     */
    public $this setAction(?string $action);
}
```

### [Request](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/Request.php)

```php
/**
 * Represents the data used to construct a SOAP request, after the arguments for the call have been used to
 * generate an XML document. Modifications that are made in SoapClient#onBeforeRequest() will be reflected in
 * the data that is passed to SoapClient#onRequest().
 */
final class \DaveRandom\HookableSoapClient\Request
{
    /**
     * Get the DOMDocument that represents the XML that will be sent in the body of the request.
     */
    public \DOMDocument getDocument();

    /**
     * Set a new DOMDocument that represents the XML that will be sent in the body of the request.
     */
    public $this setDocument(\DOMDocument $document);

    /**
     * Determine whether the request has a defined (not null) target URI.
     */
    public bool hasUri();

    /**
     * Get the target URI.
     */
    public ?string getUri();

    /**
     * Set the target URI.
     */
    public $this setUri(?string $uri);

    /**
     * Determine whether the request has a defined (not null) SOAP action.
     */
    public bool hasAction();

    /**
     * Get the SOAP action.
     */
    public ?string getAction();

    /**
     * Set the SOAP action.
     */
    public $this setAction(?string $action);

    /**
     * Get the SOAP version.
     */
    public int getVersion();

    /**
     * Set the SOAP version.
     */
    public $this setVersion(int $version);

    /**
     * Determine whether the underlying SoapClient is expecting a non-empty response document.
     */
    public bool isResponseExpected();

    /**
     * Get the CallData object associated with this request.
     * Modifications made to the call data associated with a Request instance will have no effect.
     */
    public CallData getCallData();
}
```

#### [Response](https://github.com/DaveRandom/HookableSoapClient/blob/master/src/Response.php)

```php
final class \DaveRandom\HookableSoapClient\Response
{
    /**
     * Determine whether the response has a defined (not null) XML document.
     */
    public bool hasDocument();

    /**
     * Get the response XML document.
     */
    public ?\DOMDocument getDocument();

    /**
     * Set the response XML document.
     */
    public void setDocument(?\DOMDocument $document); 

    /**
     * Get the Request object associated with the response.
     */
    public Request getRequest();
}
```
