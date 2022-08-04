# Go multi-platform build tool

Made for inclusion into pipelines to build a Go binary for multiple platforms.

## Requirements

- [Go](https://golang.org/doc/install)
- [PHP](https://www.php.net/downloads.php)
- [Bash](https://www.gnu.org/software/bash/manual/bash.html)

## Installation

Get it via
```bash
$ wget https://raw.githubusercontent.com/ohanome/go-multibuilder/main/build.php
```

## Usage

```bash
$ php build.php <VERSION>
```

`<VERSION>` must be following the [semver](https://semver.org) standards.

## Example

```bash
#!/bin/bash
wget https://raw.githubusercontent.com/ohanome/go-multibuilder/main/build.php
php build.php 1.0.0
```