## Image-Faker


Image Faker is a web app built to generate images dynamically, using simple urls.

```
http://localhost:8000/6d3353/300x150.png
```

### Parameters

*Image size*

You can define the width and height of the image in pixels, separated with x. The maximum value for both is 1500.
```
http://localhost:8000/300x150.png
```

Height is optional. If not defined, it will be equal to width.
```
http://localhost:8000/300.png
```

*Extension*
It can be jpg, png or gif.
```
http://localhost:8000/300.jpg
```

*Background color*

Optional. Hexadecimal code, 6 (or 3) characters. The default color is 000000 (black).
```
http://localhost:8000/6d3353/300x150.png
```

### Code

I started this project with some goals in mind:

* Practice TDD.
* Play with Composer and Git.
* Try to create good code ;-)

Image Faker uses Silex, a PHP microframework created by Fabien Potencier and Igor Wiedler. The images are generated using Imagine, a PHP library for image manipulation created by Bulat Shakirzyanov.

### License

Image Faker's code is under the open-source MIT License. The documentation is under Creative Commons Attribution 3.0 Unported (CC BY 3.0).

This project is inspired in Dynamic Dummy Image Generator by Russell Heimlich.

### Change log

* 1.0.0 (Juny 8, 2013): initial release.

### Road map

* Personalize font color.
* Personalize default values in a config file.
* Work with Imagick and Gmagick (actually only works with GD).
* Use the HTTP cache.
* Default image sizes: NTSC, PAL, ...

### Author

My name is Rober Martín. I'm a developer from Donostia / San Sebastián.
