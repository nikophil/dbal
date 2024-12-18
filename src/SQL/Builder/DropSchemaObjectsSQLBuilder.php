<?php

declare(strict_types=1);

namespace Doctrine\DBAL\SQL\Builder;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;

use function array_merge;

final class DropSchemaObjectsSQLBuilder
{
    public function __construct(private readonly AbstractPlatform $platform)
    {
    }

    /** @return list<string> */
    public function buildSQL(Schema $schema): array
    {
        return array_merge(
            $this->buildSequenceStatements($schema->getSequences()),
            $this->buildTableStatements($schema->getTables()),
            $this->buildNamespaceStatements($schema->getNamespaces()),
            $this->buildViewStatements($schema->getViews()),
        );
    }

    /**
     * @param list<Table> $tables
     *
     * @return list<string>
     */
    private function buildTableStatements(array $tables): array
    {
        return $this->platform->getDropTablesSQL($tables);
    }

    /**
     * @param list<Sequence> $sequences
     *
     * @return list<string>
     */
    private function buildSequenceStatements(array $sequences): array
    {
        $statements = [];

        foreach ($sequences as $sequence) {
            $statements[] = $this->platform->getDropSequenceSQL($sequence->getQuotedName($this->platform));
        }

        return $statements;
    }

    /**
     * @param list<string> $namespaces
     *
     * @return list<string>
     */
    private function buildNamespaceStatements(array $namespaces): array
    {
        if (! $this->platform->supportsSchemas()) {
            return [];
        }

        $statements = [];

        foreach ($namespaces as $namespace) {
            $statements[] = $this->platform->getDropSchemaSQL($namespace);
        }

        return $statements;
    }

    /**
     * @param list<View> $views
     *
     * @return list<string>
     */
    private function buildViewStatements(array $views): array
    {
        $statements = [];

        foreach ($views as $view) {
            $statements[] = $this->platform->getDropViewSQL($view->getQuotedName($this->platform));
        }

        return $statements;
    }
}
