<?php
namespace Pion\Support\Collection;

use Illuminate\Support\Collection;

/**
 * Class GroupedCollection
 *
 * Collection that will support adding values with grouped index (collection indexed by groupKey)
 *
 * @package Pion\Support
 */
class GroupedCollection extends Collection
{
    /**
     * Adds the grouped collection
     *
     * @param string $groupKey
     * @param mixed $value
     * @param string|null $key
     *
     * @return $this
     */
    public function add($groupKey, $value, $key = null)
    {
        // get the group, if the group is not created
        // null will be returned
        $group = $this->get($groupKey);

        // create new empty group
        if (is_null($group)) {
            $group = new Collection();
            $this->put($groupKey, $group);
        }

        // add the value to the collection
        $group->put($key, $value);

        return $this;
    }
}