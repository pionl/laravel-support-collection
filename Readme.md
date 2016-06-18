# Laravel Collection support package

A mix of classes for improving the Collection.

## Instalation

    composer require pion/laravel-support-collection

## NestedCollection

Creates a nested collection from flat collection to child nested collection based on parent property and id property.

All childs are stored to childs property in the object. Can be empty.

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
                                
                                
## Eloquent usage

It's advised to set a property in the Eloquent model to prevent creating childs into the attributes. For default property
name you can use the `ChildsCollectionTrait`. This provides a public `$childs` property for your Eloquent model

## GroupedCollection

Enables a quick how to build a grouped collection. Just add a new item to the collection with given group key and the value.
Optionally you can provide also the key for the value

    public function add($groupKey, $value, $key = null)