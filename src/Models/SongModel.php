<?php

namespace App\Models;

use Guillermoandrae\Models\AbstractModel;

final class SongModel extends AbstractModel
{
    final public function getArtist(): string
    {
        return $this->offsetGet('Artist');
    }

    final public function getSongTitle(): string
    {
        return $this->offsetGet('SongTitle');
    }
}
