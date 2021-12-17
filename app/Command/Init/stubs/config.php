<?php

$this->env('default', function () {
    $this->action('start', function () {
        $this->task('Valet', 'valet start');
        $this->task('DBngin', 'open /Applications/DBngin.app');
    });


    $this->action('stop', function () {
        $this->task('Valet', 'valet stop');
        $this->task('DBngin', 'open /Applications/DBngin.app');
    });
});
