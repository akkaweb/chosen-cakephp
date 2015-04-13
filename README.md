
# ChosenHelper for CakePHP 3

ChosenHelper is a class for integrating HarvestHQ [Chosen](https://github.com/harvesthq/chosen/) select boxes in CakePHP 3. Check out HarvestHQ's [demo](http://harvesthq.github.com/chosen/) for documentation and usage.

[![Build Status](https://travis-ci.org/paulredmond/chosen-cakephp.png?branch=master,3.0)](https://travis-ci.org/paulredmond/chosen-cakephp)

Changelog
---------
A [Changelog Wiki page](https://github.com/paulredmond/chosen-cakephp/wiki/Changelog) is now available. Review it carefully to make sure you do not upgrade permaturely.

Installation
------------

Chosen CakePHP 3 plugin supports [Composer](https://github.com/composer/composer) and [Packagist](http://packagist.org/). After you [download](http://packagist.org/) composer.phar and put it in your path:

Composer will take care of installing the plugin into the correct location. Include the following `composer.json` file at `path/to/app`

```json
{
    "require": {
        "paulredmond/chosen-cakephp": "*"
    }
}
```

_Use a sensible stable version for the plugin. The above '*' is only intended as an example._

```bash
cd path/to/app
php composer.phar install
```

Bootstrap the plugin in app/Config/bootstrap.php:

```php
<?php

// ...

Plugin::load('Chosen');

?>
```

### Optional webroot symlink
```console
cd /path/to/app/webroot
ln -s ../plugins/Chosen/webroot chosen
```

Setup
-----

In /src/Controller/AppController.php:

```php
<?php

public $helpers = [
    'Chosen.Chosen',
];
```

Out of the box, the ChosenHelper will work with jQuery; but you might want prototype or a custom class:

```php
<?php

public $helpers = [
    'Chosen.Chosen' => [
        'framework' => 'prototype',
        'class'     => 'chosen-custom', // Deselect-enabled class would be 'chosen-custom-deselect'
    ],
];
```

Now all classes rendered with the helper, or other ```<select>``` inputs with your configured class will be targeted.

### JQuery / Prototype
Make sure that you are loading JQuery (1.4+) or Prototype however you want:

```php
<?php

// One way in In default.ctp
echo $this->Html->js('jquery'); // sets src to /js/jquery.js
```

* Note: Chosen CSS/JS files are only loaded if the helper select method is called at least once.*

Pull Requests
-------------


Chosen CakePHP plugin has [contributions](https://github.com/paulredmond/chosen-cakephp/graphs/contributors) from the Github communitiy. I am grateful for the suggestions, fixes, and improvements. If you'd like to submit a pull request, follow these simple instructions:

  * Pull requests for the 2.1.x version should be submitted to the `2.1` branch
  * If the supported `2.0` branch (for CakePHP 2.0.x) could benefit from your Pull Request, consider opening another Pull Request for that branch.
  * The `master` branch reflects the latest stable version available.

Testing
-------
You can run tests for Chosen with phpunit from the ```app``` folder. Learn more about [Testing in CakePHP 2](http://book.cakephp.org/2.0/en/development/testing.html)

_Ensure that you have installed the vendor dependencies for this plugin through composer or some other means._

```console
./Console/cake testsuite Chosen View/Helper/ChosenHelper
```


Examples
--------
Chosen inputs behave identically to the FormHelper::input() method.

Multi-select:

```php
<?php
echo $this->Chosen->select(
    'Article.category_id',
    [1 => 'Category 1', 2 => 'Category 2'],
    ['data-placeholder' => 'Pick categories...', 'multiple' => true]
);
?>

// ================

<?php echo $this->Chosen->select('user_id', $users); ?>
```

Default selected:

```php
<?php
echo $this->Chosen->select(
    'Article.category_id',
    [1 => 'Category 1', 2 => 'Category 2'],
    [
        'data-placeholder' => 'Pick categories...',
        'default' => 1,
    ]
);
?>
```

Grouped:

```php
<?php
echo $this->Chosen->select(
    'Profile.favorite_team',
    [
        'NFC East' => [
            'Dallas Cowboys',
            'New York Giants',
            'Philadelphia Eagles',
            'Washington Redskins'
        ],
        'NFC North' => [
            'Chicago Bears',
            'Detroit Lions',
            'Greenbay Packers',
            'Minnesota Vikings'
        ],
        // ....
    ],
    [
        'data-placeholder' => 'Pick your favorite NFL team...',
        'style' => 'width: 350px'
    ]
);
?>
```

Deselect on Single Select:

```php
<?php
echo $this->Chosen->select(
    'Profile.optional',
    $options,
    ['data-placeholder' => 'Please select...', 'deselect' => true],
);
?>
```

Do not use ```'empty' => 'Please Select...'``` attribute with deselect, use ```'data-placeholder' => 'Please Select...'``` instead.

License
-------
Copyright 2015 Paul Redmond. It is free software, and may be redistributed under the terms specified in the LICENSE file. License is also available [online](http://paulredmond.mit-license.org/).
