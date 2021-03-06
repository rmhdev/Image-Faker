# Image Faker

Image Faker is a `PHP` application built to generate images dynamically, using simple urls.

[![Build Status](https://travis-ci.org/rmhdev/Image-Faker.svg)](https://travis-ci.org/rmhdev/Image-Faker)

## Example

Calling to this URL:

```
http://example.org/6d3353/300x150.png
```

Will return the next image:

![Fake Image](docs/300x150.png)

## Demo

Are you interested? [Test Image Faker][] by yourself!

## URL parameters

You can customize 4 aspects of the images:

* Size (width and height)
* Extension
* Background color
* Font color

**Size**

You can define the width and height of the image in pixels, separated with `x`. 
The maximum default value for both is 2000.

```
http://example.org/300x150.png
```

Height is optional. If not defined, it will be equal to width.

```
http://example.org/300.png
```

Some standard sizes are available (`ntsc`, `pal`, `hd720`, `hd1080`).

```
http://example.org/ntsc.png
```

**Extension**

It can be `jpg`, `png` or `gif`.

```
http://example.org/300.jpg
```

**Background color**

Optional. Hexadecimal code, 6 (or 3) characters. The default color is `000000` (black).

```
http://example.org/6d3353/300x150.png
```

**Font color**

Optional, defined after background color. Hexadecimal code, 6 (or 3) characters. If not defined, is automatically calculated to contrast with the background color.

```
http://example.org/555555/ffff00/300x150.png
```

### Standard image sizes

To ease image creation, some standard image sizes are available:

* ntsc: `720x480`
* pal: `768x576`
* hd720: `1280x720`
* hd1080: `1920x1080`

You can [define your own sizes](#customization).

### HTTP cache

Creating images dynamically is an "complex" task executed by the server. If images are too big or a lot of requests are made at the same time, the overhead can become a problem. Luckily HTTP cache is here to help, storing responses temporarily and improving communication between users and server.

## Installation

Get the source code of the project from GitHub. You have two options:

A. Clone it:

```bash
git clone git@github.com:rmhdev/image-faker.git
```

B. Download it:

Check the [latest release](https://github.com/rmhdev/image-faker/releases) and copy it to your installation folder.

### Project dependencies

Retrieve all the dependencies using [Composer](http://getcomposer.org/).
Install it and then run the `install` command:

```bash
php composer.phar install
```

### Server configuration

This project is built using [Silex][].
The official docs will give you more information about [how to configure your server][]. Make sure that:

- the **document root** points to the `image-faker/web/` directory.
- folders in `image-faker/var/` must be **writable** by the web server.

### Play with Image Faker

If you are using PHP 5.4+, its built-in web server will help you to play with this project:

```bash
cd image-faker/
php -S localhost:8080 -t web web/index.php
```

Easy, right? Just open a browser and enter `http://localhost:8080`

## Customization

The default parameters are defined in `config/parameters.dist.php`:

```php
$app["image_faker.parameters"] = array(
    "library"           => "gd",    // choose between "gd", "imagick" and "gmagick"
    "background_color"  => null,    // hexadecimal
    "color"             => null,    // hexadecimal
    "cache_ttl"         => 3600,    // seconds
    "max_width"         => 2000,    // pixels
    "max_height"        => 2000,    // pixels
    "sizes"             => array(
        // "lorem" => "300x400"
    ), 
);
```

If you want to customize them, just copy the file and rename it to `parameters.php`.

## Unit tests

Check the [Travis page][] to see the build status.
If you want to run the tests by yourself, execute the next command:

```bash
php ./vendor/bin/phpunit
```

You'll need to install [PHPUnit][] via `composer` if you haven't yet.

## Code

I started this project with some goals in mind:

* Practice `TDD`.
* Play with `Composer` and `Git`.
* Try to create good code ;-)

Image Faker uses [Silex][], a `PHP` microframework created by [Fabien Potencier][] and [Igor Wiedler][]. 
The images are generated using [Imagine][], a `PHP` library for image manipulation created by [Bulat Shakirzyanov][].

## License

Image Faker's code is under the open-source [MIT License][]. 
The documentation is under Creative Commons Attribution 3.0 Unported ([CC BY 3.0][]).

This project is inspired in [Dynamic Dummy Image Generator][] by [Russell Heimlich][].

## Change log

* `2.0.0` (October 18, 2015): customizable app parameters.
* `1.4.0` (July 23, 2013): work with `GD`, `Imagick` and `Gmagick`.
* `1.3.0` (July 5, 2013): use HTTP cache.
* `1.2.0` (June 23, 2013): font color can be indicated.
* `1.1.0` (June 22, 2013): added first standard image sizes (`NTSC`, `PAL`, `HD720` and `HD1080`).
* `1.0.0` (June 9, 2013): initial release.

## Road map

* Info about colors when no image is requested.

## Author

My name is [Rober Martín][] ([@rmhdev][]). I'm a developer from Donostia / San Sebastián.

[Test Image Faker]: http://image-faker.rmhdev.net/
[Silex]: http://silex.sensiolabs.org/
[how to configure your server]: http://silex.sensiolabs.org/doc/web_servers.html
[Fabien Potencier]: http://fabien.potencier.org/
[Igor Wiedler]: https://igor.io/
[Imagine]: http://imagine.readthedocs.org/
[Bulat Shakirzyanov]: http://avalanche123.com/
[Travis page]: https://travis-ci.org/rmhdev/Image-Faker
[PHPUnit]: http://phpunit.de
[MIT License]: http://opensource.org/licenses/MIT
[CC BY 3.0]: http://creativecommons.org/licenses/by/3.0/
[Dynamic Dummy Image Generator]: http://dummyimage.com/
[Russell Heimlich]: http://www.russellheimlich.com/blog/
[Rober Martín]: http://rmhdev.net/
[@rmhdev]: http://twitter.com/rmhdev
