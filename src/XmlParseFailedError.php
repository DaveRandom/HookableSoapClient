<?php declare(strict_types=1);

namespace DaveRandom\HookableSoapClient;

// Extend from error instead of Exception because when we throw this externally
// it's indicative of an internal PHP problem
final class XmlParseFailedError extends \Error { }
