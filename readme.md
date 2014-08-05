# Nemex

This app is taken from <http://nemex.io>.
This readme is taken from <https://github.com/ianchanning/nemex>

Its a simple way of creating notes on your own webserver.
Notes written in [markdown](http://daringfireball.net/projects/markdown/).
No database required.

This repo contains several small bugfixes over version 0.98 (from <http://nemex.io>)

## Requirements

- [PHP](http://php.net) >= 5.2.
- Required PHP Extensions:
    - [GD](http://br2.php.net/manual/en/book.image.php).
    - [iconv](http://br1.php.net/manual/en/book.iconv.php). (optional, but desired)

## Installation

1. No source control:
	1. Download the zip file
	2. Unzip it and FTP the files to your webserver e.g httpdocs/nemex
	3. Make sure the /projects folder is writable by PHP.

## Configuration

1. Edit `usr` and `pwd` in config.php
1. Go to <http://www.mydomain.com/nemex>

## License

GPL v3

## Contributors

[The full and righteous list on this link][1], but special kudos to:

- [nemex](https://twitter.com/nemex_io).
- [Joel Limberg](https://github.com/joellimberg/nemex).
- [Iann Channing](https://github.com/ianchanning/nemex).

[1]: https://github.com/augustohp/nemex/graphs/contributors
