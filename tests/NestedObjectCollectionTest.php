<?php

namespace Pion\Support\Collection\Tests;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Pion\Support\Collection\NestedObjectCollection;

class NestedObjectCollectionTest extends TestCase
{
    public function testItBuildsNestedCollectionsFromFlatItems(): void
    {
        $items = new Collection([
            (object) ['id' => 1, 'parent_id' => null, 'name' => 'root'],
            (object) ['id' => 2, 'parent_id' => 1, 'name' => 'child'],
            (object) ['id' => 3, 'parent_id' => 2, 'name' => 'grandchild'],
        ]);

        $nested = new NestedObjectCollection($items);
        $root = $nested->getCollection()->first();

        $this->assertSame('root', $root->name);
        $this->assertCount(1, $root->childs);
        $this->assertSame('child', $root->childs->first()->name);
        $this->assertCount(1, $root->childs->first()->childs);
        $this->assertSame('grandchild', $root->childs->first()->childs->first()->name);
    }

    public function testItUsesCollectionMethodsViaMagicCall(): void
    {
        $items = new Collection([
            (object) ['id' => 1, 'parent_id' => null, 'name' => 'root'],
            (object) ['id' => 2, 'parent_id' => 1, 'name' => 'child'],
        ]);

        $nested = new NestedObjectCollection($items);

        $this->assertSame(['root'], $nested->pluck('name')->all());
    }

    public function testItSupportsCustomRootKeyAndChildrenProperty(): void
    {
        $items = new Collection([
            (object) ['uuid' => 10, 'parent_uuid' => 0, 'name' => 'root'],
            (object) ['uuid' => 11, 'parent_uuid' => 10, 'name' => 'child'],
        ]);

        $nested = new NestedObjectCollection($items, 'parent_uuid', 'uuid', 0, 'children');
        $root = $nested->getCollection()->first();

        $this->assertSame('root', $root->name);
        $this->assertObjectNotHasProperty('childs', $root);
        $this->assertCount(1, $root->children);
        $this->assertSame('child', $root->children->first()->name);
    }
}
