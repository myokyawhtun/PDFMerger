# tFPDF
**This repository is only made for cloning official tFPDF releases which are available at: 
http://fpdf.org/en/script/script92.php THERE WILL BE NO DEVELOPMENT IN THIS REPOSITORY!**

_The only change in this version is that the require_once() calls to font/unifont/ttfonts.php
are commented and resolved through the composer autoloader. The demo ex.php was changed
accordingly, too._

tFPDF accepts UTF-8 encoded text. It embeds font subsets allowing small PDF files.

It requires a folder 'unifont' as a subfolder of the 'font' folder.

You should make the 'unifont' folder writeable (CHMOD 755 or 644). Although this
is not essential, it allows caching of the font metrics the first time a font is used,
making subsequent uses much faster.

All tFPDF requires is a .ttf TrueType font file. The file should be placed in the
'unifont' directory. Optionally, you can also define the path to your system fonts e.g. 'C:\Windows\Font'
(see the example ex.php file) and reference TrueType fonts in this directory.

Pass a fourth parameter as true when calling AddFont(), and use utf-8 encoded text 
when using Write() etc.

## Installation with [Composer](https://packagist.org/packages/setasign/tfpdf)

If you're using Composer to manage dependencies, you can use

    $ composer require setasign/tfpdf:1.31

or you can include the following in your composer.json file:

```json
{
    "require": {
        "setasign/tfpdf": "1.31"
    }
}
```

## Usage

Notice that tFPDF is not name-spaced. You can extend the class this way:

```php 
namespace your\namespace;
    
class Document extends \tFPDF
```

or create an instance this way:

```php 
$pdf = new \tFPDF();
```
