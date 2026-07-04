<?php

namespace Pion\Support\Collection\Tests;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Pion\Support\Collection\GroupedCollection;

class GroupedCollectionTest extends TestCase
{
    public function testAddToGroupCreatesAndReusesGroupedCollections(): void
    {
        $grouped = new GroupedCollection();

        $grouped->addToGroup('admins', 'alice', 'first');
        $grouped->addToGroup('admins', 'bob', 'second');

        $this->assertInstanceOf(Collection::class, $grouped->get('admins'));
        $this->assertSame(
            ['first' => 'alice', 'second' => 'bob'],
            $grouped->get('admins')->all()
        );
    }

    public function testAddToGroupIsChainableAcrossGroups(): void
    {
        $grouped = (new GroupedCollection())
            ->addToGroup('admins', 'alice', 'first')
            ->addToGroup('editors', 'eve', 'first');

        $this->assertSame(['first' => 'alice'], $grouped->get('admins')->all());
        $this->assertSame(['first' => 'eve'], $grouped->get('editors')->all());
    }
}
