<div style="float: right;">
	<a href="https://github.com/glhd/linen/actions" target="_blank">
		<img 
			src="https://github.com/glhd/linen/workflows/PHPUnit/badge.svg" 
			alt="Build Status" 
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
    <a href="https://bsky.app/profile/cmorrell.com" target="_blank">
        <img 
            src="https://img.shields.io/bluesky/followers/cmorrell.com" 
            alt="Follow @cmorrell.com on bsky" 
        />
    </a>
</div>

<h1>
    <img src="art/linen.png" height="147" alt="Linen" />
</h1>

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
