## Image-Faker

Image Faker is a web app built to generate images dynamically, using simple urls.

```
http://example.org/6d3353/300x150.png
```

Are you interested? [Test Image Faker][] by yourself!

### Parameters

**Image size**

You can define the width and height of the image in pixels, separated with `x`. The maximum value for both is 1500.

```
http://example.org/300x150.png
```

Height is optional. If not defined, it will be equal to width.

```
http://example.org/300.png
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

* `1.0.0` (Juny 9, 2013): initial release.

### Road map

* Personalize font color.
* Personalize default values in a config file.
* Work with `Imagick` and `Gmagick` (actually only works with `GD`).
* Use the `HTTP` cache.
* Default image sizes: `NTSC`, `PAL`, ...

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
