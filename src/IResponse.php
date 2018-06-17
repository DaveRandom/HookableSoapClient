<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

interface IResponse
{
    public function hasDocument(): bool;

    public function getDocument(): ?\DOMDocument;

    public function setDocument(?\DOMDocument $document): void;

    public function getRequest(): Request;
}
