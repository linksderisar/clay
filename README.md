# Clay

## Installation
 ```bash
composer require linksderisar/clay
```
## Basic Usage
```php
\Linksderisar\Clay\Components\Base\Component::make('div')->toJson();

// Or return it directly from the Controller

Route::get('/', function () {
    return \Linksderisar\Clay\Components\Base\Component::make('div');
});
```

### Add Attributes
You can add all html Attributes like id, class, placeholder... with the attribute() or attributes() function from the ```Base/Component```
```php
\Linksderisar\Clay\Components\Base\Component::make('div')
    ->attribute('id', 'identifier');

// Or add multiple Attributes at once with attributes(array)     

\Linksderisar\Clay\Components\Base\Component::make('div')
    ->attributes(['id' => 'identifier']);
```

### Add Classes
You can add classes to your tag using the ```attribute('class','my-css-class')``` or you use the 
```classes()``` function. The classes() function excepts strings and arrays.
```php
\Linksderisar\Clay\Components\Base\Component::make('div')
    ->classes('my-css-class my-other-css-class');

\Linksderisar\Clay\Components\Base\Component::make('div')
    ->classes(['my-css-class', 'my-other-css-class']);

\Linksderisar\Clay\Components\Base\Component::make('div')
    ->classes([
        'my-css-class' => true,
        'css-class-who-wont-be-added-in-the-dom' => false
    ]);
```

### Add Props
You can add vue props using the ```prop('class','my-css-class')``` function.
```php
\Linksderisar\Clay\Components\Base\Component::make('div')
    ->prop('my-Prop', 'prop-value');
        
\Linksderisar\Clay\Components\Base\Component::make('div')
    ->props(['my-Prop'=> 'prop-value']);
```

### Examples
#### An overwhelming example

PHP Code:
```php
 return Page::create(
        Component::make('div')
            ->key('someKey')
            ->ref('ref')
            ->refInFor()
            ->classes('some-class')
            ->prop('staticProp', 'value')
            ->if('a === b')
            ->show('c === b')
            ->loop('array/object')
            ->style('background-color', 'black')
            ->children(
                Component::make('span')->text('Some Text between the Tags'),
                Component::make('some-component')
                    ->scopedSlots(function ($scope) {
                        return [Component::make('div')
                            ->on('someEvent', $scope('scoped.method()'))
                            ->bind(function ($component) use ($scope) {
                                return $component->prop('boundProp', $scope('scoped.value'));
                            })];
                    })
            )
    )
        ->store(['variable' => 'value'])
        ->header([
            'title' => 'Titel der seite',
            'link' => ['rel' => 'stylesheet', 'href' => '/some/css.css']
        ]);
```
Generated Json Blueprint:
```json
    {
        "store": {
            "variable": "value"
        },
        "meta": {
            "version": "1.0.0"
        },
        "head": {
            "title": "Titel der seite",
            "link": {
                "rel": "stylesheet",
                "href": "/some/css.css"
            }
        },
        "componentTree": {
            "id": "ERX1cc5HWzDuKUMF",
            "type": "div",
            "attributes": {
                "key": "someKey",
                "ref": "ref",
                "refInFor": true,
                "style": {
                    "background-color": "black"
                },
                "props": {
                    "staticProp": "value"
                },
                "class": [
                    "some-class"
                ]
            },
            "loop": "array/object",
            "if": "a === b",
            "show": "c === b",
            "children": [
                {
                    "id": "nMkHWwpuogLFqZwE",
                    "type": "span",
                    "children": {
                        "id": "Sr6cm4i2ojUH48KW",
                        "type": "$text",
                        "value": "Some Text between the Tags"
                    }
                },
                {
                    "id": "X4XzTHudtT6kxxzi",
                    "type": "some-component",
                    "scopedSlots": [
                        {
                            "id": "lPusFpNEW0LxSsGr",
                            "type": "div",
                            "attributes": {
                                "props": {
                                    ":boundProp": "$_slot_props.X4XzTHudtT6kxxzi.scoped.value"
                                },
                                "on": {
                                    "someEvent": "$_slot_props.X4XzTHudtT6kxxzi.scoped.method()"
                                }
                            }
                        }
                    ]
                }
            ]
        }
    }
```



### Blueprints
 - **protected** generateId()
 - **public** clone()
 - **public static** create(...$attributes)
 - **public** toArray()
 - **public** toJson()
 - **public** __toString()

### ComponentBlueprint

### ConditionBlueprint

#### IfConditionBlueprint

#### ShowConditionBlueprint

### TextBlueprint

### LoopBlueprint

