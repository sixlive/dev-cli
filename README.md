```
██████  ███████ ██    ██      ██████ ██      ██
██   ██ ██      ██    ██     ██      ██      ██
██   ██ █████   ██    ██     ██      ██      ██
██   ██ ██       ██  ██      ██      ██      ██
██████  ███████   ████        ██████ ███████ ██
```

A configurable development environment CLI.

## Installation

```bash
dev-cli install
```

## Usage

```bash
dev-cli list
```

## Configurations

### Configuration Directory
```bash
$HOME/.config/dev-cli
```

### Example Configuration

Environment configurations can live in a single or multiple files in within the
configuration directory.

```php
// $HOME/.config/dev-cli/default.php

<?php

$this->env('default', function () {
    $this->action('start', function () {
        $this->description('Start the default dev env');
        $this->task('Valet', 'valet start');
        $this->task('Open DBngin', 'open /Applications/DBngin.app');
    });


    $this->action('stop', function () {
        $this->description('Stop the default dev env');
        $this->task('Valet', 'valet stop');
        $this->task('Open DBngin', 'open /Applications/DBngin.app');
    });
});
```

```php
// $HOME/.config/dev-cli/example.php

<?php

$this->env('example', function () {
    $this->action('start', function () {
        $this->description('Start the default dev env');
        $this->task('Valet', 'valet start');
        $this->task('Open DBngin', 'open /Applications/DBngin.app');
    });


    $this->action('stop', function () {
        $this->description('Stop the default dev env');
        $this->task('Valet', 'valet stop');
        $this->task('Open DBngin', 'open /Applications/DBngin.app');
    });
});

```

**NOTE**: the `default` env will hoist all actions to top level commands. e.g.
`dev-cli start` vs `dev-cli example:start`.

```bash
  USAGE: dev <command> [options] [arguments]

  completion         Dump the shell completion script
  install            install dev-cli
  self-update        Allows to self-update a build application
  start              Start the default dev env
  stop               Stop the default dev env

  example:start      Start the default dev env
  example:stop       Stop the default dev env
```

## Development
### Build

```bash
./dev-cli app:build

or

./dev-cli app:build --build-version="v1.0.0"
```
