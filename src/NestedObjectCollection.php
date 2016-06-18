<?php
namespace Pion\Support\Collection;

use Illuminate\Support\Collection;

/**
 * Class NestedObjectCollection
 *
 * Creates a nested collection from flat collection to child nested collection based on parent property and id property.
 *
 * All childs are stored to childs property in the object. Can be empty.
 *
 * @method array toArray()
 * @method boolean isEmpty()
 *
 * @package App\Classes
 */
class NestedObjectCollection
{
    /**
     * @var static
     */
    protected $groupedCollection;

    /**
     * The builded collection
     * @var Collection
     */
    protected $collection;

    /**
     * The property to get the parent id value that is used for grouping
     * @var string
     */
    protected $parentProperty;

    /**
     * The property name for stored id
     * @var string
     */
    protected $idProperty;

    /**
     * the index key for getting the root elements. When the parent property value
     * returns null, it will be empty string.
     * @var string
     */
    protected $rootIndexKeyOnGroup;
    /**
     * @var string
     */
    protected $propertyForChildren;

    /**
     * NestedObjectCollection constructor.
     *
     * Groups the items by the parent property and loops from the top of tree (null property or different value) and
     * adds childs to the object.
     *
     * @param Collection $items
     * @param string $parentProperty the property name to get the parent id
     * @param string $idProperty the property name to get the id value
     * @param string $rootIndexKeyOnGroup the index key for getting the root elements. When the parent property value
     * returns null, it will be empty string.
     * @param string $propertyForChildren   the property name to store the children
     */
    public function __construct(Collection $items, $parentProperty = "parent_id", $idProperty = "id",
                                $rootIndexKeyOnGroup = "", $propertyForChildren = "childs")
    {
        // group the collection by the parent property value
        $this->groupedCollection = $items->groupBy($parentProperty);

        // store the parent property name
        $this->parentProperty = $parentProperty;
        $this->idProperty = $idProperty;
        $this->rootIndexKeyOnGroup = $rootIndexKeyOnGroup;
        $this->propertyForChildren = $propertyForChildren;

        $this->collection = $this->buildCollection();
    }

    /**
     * Builds the collection with the nested structure
     * @return Collection
     */
    protected function buildCollection()
    {
        // create empty collection that we will add children
        $collection = new Collection();

        // add children to the collection
        $this->addChildsItems($this->rootIndexKeyOnGroup, $collection);

        return $collection;
    }

    /**
     * Creates a nested collection
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Checks the grouped collection if given key is in the source and ads the childs to collection
     * @param $key
     * @param Collection $collection
     * @return $this
     */
    protected function addChildsItems($key, Collection $collection) {

        $childs = $this->groupedCollection->get($key);

        if (!is_null($childs)) {
            foreach ($childs as $child) {
                // create empty collection and add to the children
                $child->{$this->propertyForChildren} = new Collection();

                // try to add children's of the child
                $this->addChildsItems($child->{$this->idProperty}, $child->{$this->propertyForChildren});

                // store to the collection
                $collection->push($child);
            }
        }

        return $this;
    }

    /**
     * Passes the function into the collection
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getCollection(), $name], $arguments);
    }

}