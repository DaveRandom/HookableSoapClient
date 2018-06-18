<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

abstract class TypedArrayObject extends \ArrayObject
{
    public function __construct(array $input = null, int $flags = null, string $iteratorClass = null)
    {
        $input = $input ?? [];

        foreach ($input as $value) {
            $this->checkType($value);
        }

        parent::__construct($input, $flags ?? 0, $iteratorClass ?? \ArrayIterator::class);
    }

    public function offsetSet($index, $value): void
    {
        $this->checkType($value);

        parent::offsetSet($index, $value);
    }

    abstract protected function checkType($value): void;
}
