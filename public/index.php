<?php
ini_set('memory_limit', '1024M');
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

function pr($data, $exit = 0, $var_dump = 0) {
    
    if (!isset($_SERVER['REMOTE_ADDR']) || $_SERVER['REMOTE_ADDR'] == '195.138.85.195' || strpos($_SERVER['REMOTE_ADDR'],'172.30.1.')===0) {
        static $clear;

        if (!$clear) {
            if (ob_get_length())
                ob_end_clean();
            $clear = true;
        }

        if (isset($_SERVER['SERVER_ADDR'])) {
            echo "<pre>";
        }

        if ($var_dump) {
            var_dump($data);
        } else {
            print_r($data);
        }

        if (isset($_SERVER['SERVER_ADDR'])) {
            echo "</pre>";
        } else {
            echo "\n";
        }

        if ($exit) {
            exit;
        }
    }
}