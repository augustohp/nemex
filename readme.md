# Nemex [![Build Status](https://travis-ci.org/augustohp/nemex.svg)](https://travis-ci.org/augustohp/nemex)

A **simple** [way of creating notes][4], on your own webserver,
using [markdown](http://daringfireball.net/projects/markdown/).

This is not the [official][3] repository, I intend to improve
this code for personal usage.

## Requirements

- [PHP](http://php.net) >= 5.4.
- Required PHP Extensions:
    - [GD](http://br2.php.net/manual/en/book.image.php).
    - [iconv](http://br1.php.net/manual/en/book.iconv.php). (optional, but desired)

## Installation

You have two different methods of installation:

### I have my own PHP

1. Download the zip file
2. Unzip it and FTP the files to your webserver e.g httpdocs/nemex
3. Make sure the /projects folder is writable by PHP.

### You know nothing, John Snow!

This requires some work (~10min, depending on your internet), but it is pretty
easy:

1. Download and install [VirtualBox](http://www.virtualbox.org/).
2. Download and install [Vagrant](http://www.vagrantup.com/downloads).
3. [Download](https://github.com/augustohp/nemex/archive/master.zip) Nemex.
4. Install it on a directory where you would keep your projects.
5. Open a terminal and go to the application directory.
6. Type: `vagrant up` and hit `ENTER`. Wait. :panda:
7. Open your browser at http://192.168.42.03.xip.io/
8. Profit and thanks [Nemex][2].

## Configuration

2. Copy `config.php-dist` to `config.php`.
1. Edit `usr` and `pwd` in `config.php`.
1. Go to <http://www.mydomain.com/nemex>

## License

GPL v3

## Contributors

[The full and righteous list on this link][1], but special kudos to:

- [nemex][2].
- [Joel Limberg](https://github.com/joellimberg/nemex).
- [Iann Channing](https://github.com/ianchanning/nemex).

[1]: https://github.com/augustohp/nemex/graphs/contributors
[2]: https://twitter.com/nemex_io
[3]: http://nemex.io
[4]: https://vimeo.com/102683209
