<?php

namespace AppTest\Models;

use App\Models\PostModel;
use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
{
    public function testToArray()
    {
        $expectedData = ['test' => 'this', 'black' => 'man'];
        $model = new PostModel($expectedData);
        $this->assertSame($expectedData, $model->toArray());
    }
}
