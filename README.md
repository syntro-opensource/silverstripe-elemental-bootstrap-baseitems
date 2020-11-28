# SilverStripe elemental bootstrap base items

![🎭 Tests](https://github.com/syntro-opensource/silverstripe-elemental-bootstrap-baseitems/workflows/%F0%9F%8E%AD%20Tests/badge.svg)
[![phpstan](https://img.shields.io/badge/PHPStan-enabled-success)](https://github.com/phpstan/phpstan)
[![codecov](https://codecov.io/gh/syntro-opensource/silverstripe-elemental-bootstrap-baseitems/branch/master/graph/badge.svg)](https://codecov.io/gh/syntro-opensource/silverstripe-elemental-bootstrap-baseitems)
[![composer](https://img.shields.io/packagist/dt/syntro/silverstripe-elemental-bootstrap-baseitems?color=success&logo=composer)](https://packagist.org/packages/syntro/silverstripe-elemental-bootstrap-baseitems)



This module provides DataObjects for sections in the
[`syntro/recipe-elemental-bootstrap`](https://github.com/syntro-opensource/recipe-elemental-bootstrap) recipe and other, standalone modules.

It adds an extension to the base element, which anables handling color management
for bootstrap blocks and uses a different controller template, suitable for
full-width bootstrap based themes. An additional data object is provided as a
base sub-element of a section, handling permissions and publishing.

## Requirements

* SilverStripe ^4.0
* Silverstripe elemental ^4

## Installation

```
composer require syntro/silverstripe-elemental-bootstrap-baseitems
```


## License
See [License](license.md)

## Documentation

* [Configuration](docs/en/Configuration.md)
* [Templating](docs/en/Templating.md)

## Maintainers
 * Matthias Leutenegger <hello@syntro.ch>

## Bugtracker
Bugs are tracked in the issues section of this repository. Before submitting an issue please read over
existing issues to ensure yours is unique.

If the issue does look like a new bug:

 - Create a new issue
 - Describe the steps required to reproduce your issue, and the expected outcome. Unit tests, screenshots
 and screencasts can help here.
 - Describe your environment as detailed as possible: SilverStripe version, Browser, PHP version,
 Operating System, any installed SilverStripe modules.

Please report security issues to the module maintainers directly. Please don't file security issues in the bugtracker.

## Development and contribution
If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.
