<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

final class SoapHeaderArray extends ValidatingArrayObject
{
    /**
     * @param \SoapHeader[]|\SoapHeader|null $input
     */
    public function __construct($input = null)
    {
        if ($input instanceof \SoapHeader) {
            $input = [$input];
        } else if ($input === null) {
            $input = [];
        } else if (!\is_array($input)) {
            throw new \TypeError(
                'Initial data for ' . SoapHeaderArray::class . ' must be array of ' . \SoapHeader::class
            );
        }

        parent::__construct($input);
    }

    protected function validateValue($value): void
    {
        if (!($value instanceof \SoapHeader)) {
            throw new \TypeError('SOAP header must be instance of ' . \SoapHeader::class);
        }
    }

    public function offsetGet($index): \SoapHeader
    {
        return parent::offsetGet($index);
    }
}
