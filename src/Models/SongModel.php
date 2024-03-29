<?php

namespace App\Models;

use Guillermoandrae\Models\AbstractModel;

final class SongModel extends AbstractModel
{
    public function getArtist(): string
    {
        return $this->offsetGet('Artist');
    }

    public function getSongTitle(): string
    {
        return $this->offsetGet('SongTitle');
    }
}
