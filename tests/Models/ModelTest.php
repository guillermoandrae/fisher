<?php

namespace GuillermoandraeTest\Fisher\Models;

use PHPUnit\Framework\TestCase;
use Guillermoandrae\Fisher\Models\PostModel;

final class ModelTest extends TestCase
{
    public function testToArray()
    {
        $expectedData = ['test' => 'this', 'black' => 'man'];
        $model = new PostModel($expectedData);
        $this->assertSame($expectedData, $model->toArray());
    }
}
