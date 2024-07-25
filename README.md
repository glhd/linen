<div style="float: right;">
	<a href="https://github.com/glhd/linen/actions" target="_blank">
		<img 
			src="https://github.com/glhd/linen/workflows/PHPUnit/badge.svg" 
			alt="Build Status" 
		/>
	</a>
	<a href="https://codeclimate.com/github/glhd/linen/test_coverage" target="_blank">
		<img 
			src="https://api.codeclimate.com/v1/badges/1147f60db6ef22057dbc/test_coverage" 
			alt="Coverage Status" 
		/>
	</a>
	<a href="https://packagist.org/packages/glhd/linen" target="_blank">
        <img 
            src="https://poser.pugx.org/glhd/linen/v/stable" 
            alt="Latest Stable Release" 
        />
	</a>
	<a href="./LICENSE" target="_blank">
        <img 
            src="https://poser.pugx.org/glhd/linen/license" 
            alt="MIT Licensed" 
        />
    </a>
    <a href="https://twitter.com/inxilpro" target="_blank">
        <img 
            src="https://img.shields.io/twitter/follow/inxilpro?style=social" 
            alt="Follow @inxilpro on Twitter" 
        />
    </a>
</div>

# Linen

Linen is a lightweight spreadsheet utility for Laravel. It's a simple wrapper for 
[openspout](https://github.com/openspout/openspout) with some data normalization conveniences.

## Installation

```shell
composer require glhd/linen
```

## Usage

To read a spreadsheet:

```php
foreach (Linen::read('path/to/your.xlsx') as $row) {
    // $row is a collection, keyed by the headers in snake_case
}
```

To write a spreadsheet:

```php
// $data can be any iterable/Enumerable/etc
$path = Linen::write($data, 'path/to/your.xlsx');
```
