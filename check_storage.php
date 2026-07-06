<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$routes = app('router')->getRoutes();
foreach ($routes as $route) {
    if (strpos($route->uri(), 'storage') !== false) {
        echo $route->uri() . " -> Middlewares: " . implode(', ', $route->gatherMiddleware()) . "\n";
    }
}
