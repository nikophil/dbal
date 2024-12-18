<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Schema\Exception;

use Doctrine\DBAL\Schema\SchemaException;
use LogicException;

use function sprintf;

/** @psalm-immutable */
final class NamespaceDoesNotExist extends LogicException implements SchemaException
{
    public static function new(string $namespaceName): self
    {
        return new self(sprintf('There is no namespace with name "%s" in the schema.', $namespaceName));
    }
}
