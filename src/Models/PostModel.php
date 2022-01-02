<?php

namespace App\Models;

use Guillermoandrae\Models\AbstractModel;

final class PostModel extends AbstractModel
{
    public function getOriginalAuthor(): string
    {
        return $this->offsetGet('originalAuthor');
    }

    public function getCreatedAt(): int
    {
        return $this->offsetGet('createdAt');
    }
}
