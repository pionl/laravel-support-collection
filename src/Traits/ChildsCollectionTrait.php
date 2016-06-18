<?php
namespace Pion\Support\Collection;

use Illuminate\Support\Collection;

/**
 * Class GroupedChildsTrait
 *
 * Creates a property for a childs
 *
 * @package Pion\Support\Collection
 */
trait ChildsCollectionTrait
{
    /**
     * @var Collection|null
     */
    public $childs = null;
}