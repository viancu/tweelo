<?php

// Routes
$app->get('/', 'default.controller:index');
$app->get('/cities', 'default.controller:cities');
$app->get('/position', 'default.controller:position');
$app->get('/tweets', 'default.controller:tweets');