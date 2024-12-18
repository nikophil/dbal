<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Schema\Exception;

use Doctrine\DBAL\Schema\SchemaException;
use LogicException;

use function sprintf;

/** @psalm-immutable */
final class ViewDoesNotExist extends LogicException implements SchemaException
{
    public static function new(string $viewName): self
    {
        return new self(sprintf('There exists no view with the name "%s".', $viewName));
    }
}
