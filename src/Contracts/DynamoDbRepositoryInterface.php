<?php

namespace App\Contracts;

use Guillermoandrae\Repositories\RepositoryInterface;

interface DynamoDbRepositoryInterface extends RepositoryInterface
{
    public function getTableName(): string;

    public function setModelName(string $modelName): static;

    public function getModelName(): string;
}
