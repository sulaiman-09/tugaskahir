<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// bootstrap the kernel so container & services ready
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // bind a simple Request into container
    $request = Illuminate\Http\Request::create('/sudirmanpark', 'GET', ['q' => null, 'show_all' => 0]);
    $app->instance('request', $request);

    // create a fake authenticated user so layout checks (auth()->user()->role) won't fail
    $generic = new Illuminate\Auth\GenericUser([
        'id' => 1,
        'name' => 'Dev User',
        'role' => 'admin',
    ]);
    // set as current user in the auth service
    $app['auth']->setUser($generic);

    $controller = new App\Http\Controllers\SudirmanParkController();
    $res = $controller->index();

    if ($res instanceof Illuminate\Contracts\View\View || $res instanceof Illuminate\View\View) {
        // attempt to render the blade
        $html = $res->render();
        echo "RENDER_OK\n";
        echo "LENGTH=" . strlen($html) . "\n";
    } elseif ($res instanceof Illuminate\Http\Response || $res instanceof Symfony\Component\HttpFoundation\Response) {
        echo "RESPONSE_STATUS=" . $res->getStatusCode() . "\n";
        echo "LENGTH=" . strlen($res->getContent()) . "\n";
    } else {
        echo "OK_RETURN_TYPE=" . (is_object($res) ? get_class($res) : gettype($res)) . "\n";
    }
} catch (Throwable $e) {
    echo "ERR: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
