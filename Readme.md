# Laravel Collection support package

A mix of classes for improving the Collection.

## Installation

    composer require pion/laravel-support-collection

## NestedObjectCollection

Creates a nested collection from flat collection to child nested collection based on parent property and id property.

It's expected that the values are **objects**, it will insert the childs into the desired property.

All childs are stored to childs property in the object. Can be empty.

You can call all Collection methods on the NestedObjectCollection object, all method calls are passed into the collection

### Construct
Groups the items by the parent property and loops from the top of tree (null property or different value) and
adds childs to the object.

_Parameters_

* Collection `$items`
* string `$parentProperty` the property name to get the parent id
* string `$idProperty` the property name to get the id value
* string `$rootIndexKeyOnGroup` the index key for getting the root elements. When the parent property value returns null, it will be empty string.
* string `$propertyForChildren`   the property name to store the children



    public function __construct(Collection $items, $parentProperty = "parent_id", $idProperty = "id",
                                $rootIndexKeyOnGroup = "", $propertyForChildren = "childs")
                        
### Eloquent usage

It's advised to set a property in the Eloquent model to prevent creating childs into the attributes. For default property
name you can use the `ChildsCollectionTrait`. This provides a public `$childs` property for your Eloquent model
                                
### Example

    $object1 = new stdClass();
    $object1->id = 1;
    
    // this is the root!
    $object1->parent_id = null;
    
    $object2 = new stdClass();
    
    $object2->id = 2;
    $object2->parent_id = 2;
    
    
    $object3 = new stdClass();
    
    $object3->id = 3;
    $object3->parent_id = 1;
    
    $example = new \Pion\Support\Collection\NestedObjectCollection(new \Illuminate\Support\Collection([
        $object1, $object2, $object3
    ]));
    
    $collection = $example->getCollection();
    
This example will create a collection of single item (the `$object1`). This objects will have a new property $childs
which is holding a Collection of single item `$object2` that holds again property $childs with a single item `$object3`
    
## GroupedCollection

Enables a quick how to build a grouped collection. Just add a new item to the collection with given group key and the value.
Optionally you can provide also the key for the value.

    public function add($groupKey, $value, $key = null)
    
### Example

The use of the collection is same as standard collection. 

    $groupedCollection = new GroupedCollection();
    $groupedCollection->add("1", "test 1");
    $groupedCollection->add("1", "test 2");
    $groupedCollection->add("1", "test 3");
    $groupedCollection->add("2", "test 2-1");
    $groupedCollection->add("2", "test 2-2");
    
This will result in a collection of items with a collection object. If you export the collection into array you will grouped array as expected.

    var_dump($groupedCollection->toArray());
    
    array(2) {
      [1] =>
      array(3) {
        [0] =>
        string(6) "test 1"
        [1] =>
        string(6) "test 2"
        [2] =>
        string(6) "test 3"
      }
      [2] =>
      array(2) {
        [0] =>
        string(8) "test 2-1"
        [1] =>
        string(8) "test 2-2"
      }
    }

    

