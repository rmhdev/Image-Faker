## Image-Faker

Image Faker is a web app built to generate images dynamically, using simple urls.

[![Build Status](https://travis-ci.org/rmhdev/Image-Faker.png?branch=v1.4.0)](https://travis-ci.org/rmhdev/Image-Faker)

```
http://example.org/6d3353/300x150.png
```

Are you interested? [Test Image Faker][] by yourself!

### Parameters

**Image size**

You can define the width and height of the image in pixels, separated with `x`. The maximum value for both is 2000.

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

Optional. Hexadecimal code, 6 (or 3) characters. If not defined, is automatically calculated to contrast with the background color.

```
http://example.org/ffff00/555555/300x150.png
```

#### Standard image sizes

To ease image creation, some standard image sizes are available:

* ntsc: `720x480`
* pal: `768x576`
* hd720: `1280x720`
* hd1080: `1920x1080`

#### HTTP cache

Creating images dinamically is an "complex" task executed by the server. If images are too big or a lot of requests are made at the same time, the overhead can become a problem. Luckily HTTP cache is here to help, storing responses temporarily and improving communication between users and server.

### Code

I started this project with some goals in mind:

* Practice `TDD`.
* Play with `Composer` and `Git`.
* Try to create good code ;-)

Image Faker uses [Silex][], a `PHP` microframework created by [Fabien Potencier][] and [Igor Wiedler][]. 
The images are generated using [Imagine][], a `PHP` library for image manipulation created by [Bulat Shakirzyanov][].

### License

Image Faker's code is under the open-source [MIT License][]. 
The documentation is under Creative Commons Attribution 3.0 Unported ([CC BY 3.0][]).

This project is inspired in [Dynamic Dummy Image Generator][] by [Russell Heimlich][].

### Change log

* `1.3.0` (July 5, 2013): use HTTP cache.
* `1.2.0` (Juny 23, 2013): font color can be indicated.
* `1.1.0` (Juny 22, 2013): added first standard image sizes (`NTSC`, `PAL`, `HD720` and `HD1080`).
* `1.0.0` (Juny 9, 2013): initial release.

### Road map

* Personalize default values in a config file.
* Work with `Imagick` and `Gmagick` (actually only works with `GD`).
* Draw an icon instead of text.
* Info about colors when no image is requested.

### Author

My name is [Rober Martín][] ([@rmhdev][]). I'm a developer from Donostia / San Sebastián.

[Test Image Faker]: http://image-faker.rmhdev.net/
[Silex]: http://silex.sensiolabs.org/
[Fabien Potencier]: http://fabien.potencier.org/
[Igor Wiedler]: https://igor.io/
[Imagine]: http://imagine.readthedocs.org/
[Bulat Shakirzyanov]: http://avalanche123.com/
[MIT License]: http://opensource.org/licenses/MIT
[CC BY 3.0]: http://creativecommons.org/licenses/by/3.0/
[Dynamic Dummy Image Generator]: http://dummyimage.com/
[Russell Heimlich]: http://www.russellheimlich.com/blog/
[Rober Martín]: http://rmhdev.net/
[@rmhdev]: http://twitter.com/rmhdev
