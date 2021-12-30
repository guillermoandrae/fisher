<?php

namespace App\Models;

use Guillermoandrae\Models\AbstractModel;

final class PostModel extends AbstractModel
{
    final public function getOriginalAuthor(): string
    {
        return $this->offsetGet('originalAuthor');
    }

    final public function getCreatedAt(): int
    {
        return $this->offsetGet('createdAt');
    }
}
