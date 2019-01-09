# Clay

## Installation
 ```bash
composer require linksderisar/clay
```
## Basic Usage
```php
\Linksderisar\Clay\Components\Base\Component::make('div')->toJson();

// or return it direct from the Controller

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

