<?php

namespace App\Models;

use Guillermoandrae\Models\AbstractModel;

final class BlackQuoteModel extends AbstractModel
{
    public function getAuthor(): string
    {
        return $this->offsetGet('author');
    }

    public function getCreatedAt(): int
    {
        return $this->offsetGet('createdAt');
    }

    public function getQuote(): string
    {
        return $this->offsetGet('quote');
    }
}
