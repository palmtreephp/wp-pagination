# Palmtree WordPress Pagination

WordPress pagination component for Palmtree PHP

Generates pagination for the main WP_Query or any query provided and outputs with Bootstrap v4 classes.

## Requirements
* PHP >= 7.1

## Installation

Use composer to add the package to your dependencies:
```bash
composer require palmtree/wp-pagination
```

## Usage

#### Basic
```php
<?php
$pagination = new \Palmtree\WordPress\Pagination\Pagination();

// Get Bootstrap formatted pagination
echo $pagination->getHtml();

// __toString() is also implemented, which implicitly calls getHtml()
echo $pagination;

// Or get an array of unstyled links
$links = $pagination->getLinks();

```

#### Advanced

```php
<?php
use Palmtree\WordPress\Pagination\Pagination;

$query = new \WP_Query();

$pagination = new Pagination();
$pagination->setQuery($query);
$pagination
    ->addArg('prev_text', 'Previous')
    ->addArg('next_text', 'Next');
```

## License

Released under the [MIT license](LICENSE)
