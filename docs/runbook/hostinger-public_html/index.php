<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| SkinTemple — Hostinger Bridge
|--------------------------------------------------------------------------
| Questo file va copiato in: ~/domains/prr.skintemple.it/public_html/index.php
| Il progetto Laravel va clonato in: ~/domains/prr.skintemple.it/laravel/
|
| Il document root di Hostinger punta a public_html/ ma Laravel ha bisogno
| di esporre solo public/. Questo bridge collega i due.
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../laravel/vendor/autoload.php';

$app = require_once __DIR__ . '/../laravel/bootstrap/app.php';

$app->handleRequest(Request::capture());