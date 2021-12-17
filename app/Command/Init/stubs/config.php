<?php

env('default', function () {
    $this->action('start', function () {
        task('Valet', 'valet start');
        task('DBngin', 'open /Applications/DBngin.app');
        //task('Docker', 'docker-compose...');
    });

    $this->action('stop', function () {
        task('Valet', 'valet start');
        task('DBngin', 'open /Applications/DBngin.app');
        //task('Docker', 'docker-compose..');
    });
});
