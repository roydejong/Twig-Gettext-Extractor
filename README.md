Twig Gettext Extractor [![Build Status](https://secure.travis-ci.org/umpirsky/Twig-Gettext-Extractor.svg?branch=master)](http://travis-ci.org/umpirsky/Twig-Gettext-Extractor)
======================

The Twig Gettext Extractor is [Poedit](http://www.poedit.net/download.php)
friendly tool which extracts translations from twig templates.

## This fork
Hello. Here's some things this fork offers:

- This fork has been designed for **compatibility with version 2 of twig**. It's a bit of a hack, but it works.
- Removed symfony dependencies which might make it a bit easier to install.
- You can add "dummy filters" via a `--filter` parameter to prevent the extractor from breaking when it encounters user-defined filters that would cause template parsing to break (HINT: add this *before* the `--files` part of the extractor command).
- If extraction fails for whatever reason, a log file will be dumped to the directory in which the gettext extractor library lives.

This fork is available on Packagist as a composer package for your convenience:

    composer require roydejong/twig-gettext-extractor
    
Or, to install globally (to your home directory) - recommended:

    composer global require roydejong/twig-gettext-extractor


# Original documentation

## Installation

### Manual

#### Local

Download the ``twig-gettext-extractor.phar`` file and store it somewhere on your computer.

#### Global

You can run these commands to easily access ``twig-gettext-extractor`` from anywhere on
your system:

```bash
$ sudo wget https://github.com/umpirsky/Twig-Gettext-Extractor/releases/download/1.2.0/twig-gettext-extractor.phar -O /usr/local/bin/twig-gettext-extractor
$ sudo chmod a+x /usr/local/bin/twig-gettext-extractor
```
Then, just run ``twig-gettext-extractor``.

### Composer

#### Local

```bash
$ composer require umpirsky/twig-gettext-extractor
```

#### Global

```bash
$ composer global require umpirsky/twig-gettext-extractor
```

Make sure you have ``~/.composer/vendor/bin`` in your ``PATH`` and
you're good to go:

```bash
$ export PATH="$PATH:$HOME/.composer/vendor/bin"
```
Don't forget to add this line in your `.bashrc` file if you want to keep this change after reboot.

## Setup

By default, Poedit does not have the ability to parse Twig templates.
This can be resolved by adding an additional parser (Edit > Preferences > Parsers)
with the following options:

- Language: `Twig`
- List of extensions: `*.twig`
- Invocation:
    - Parser command: `<project>/vendor/bin/twig-gettext-extractor --sort-output --force-po -o %o %C %K -L PHP --files %F` (replace `<project>` with absolute path to your project)
    - An item in keyword list: `-k%k`
    - An item in input file list: `%f`
    - Source code charset: `--from-code=%c`

<img src="http://i.imgur.com/f9px2.png" />

Now you can update your catalog and Poedit will synchronize it with your twig
templates.

## Custom extensions

Twig-Gettext-Extractor registers some default twig extensions. However, if you are using custom extensions, you need to register them first before you can extract the data. In order to achieve that, copy the binfile into some custom place. A common practice would be: `cp vendor/bin/twig-gettext-extractor bin/twig-gettext-extractor`

Now you may add your custom extensions [here](https://github.com/umpirsky/Twig-Gettext-Extractor/blob/master/twig-gettext-extractor#L41):

```php
$twig->addFunction(new \Twig_SimpleFunction('myCustomExtension', true));
$twig->addFunction(new \Twig_SimpleFunction('myCustomExtension2', true));
```

## Custom filters (in this fork)

If you're using custom, user-defined twig filters, the extractor will break when it encounters them.  

You can modify the parser command in Poedit if you want to register custom filters.

Doing so will create a dummy filter that will return the input as output without modifying it.

For example, if you have a `currency` filter, modify your parser command as follows:

    twig-gettext-extractor --sort-output --force-po -o %o %C %K -L PHP --filters currency --files %F
    
The `--filters` command must be added after the gettext parameters but before the `--files` declaration to work correctly. You can add multiple filters by delimiting them with a space.