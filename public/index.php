<?php
declare(strict_types=1);

/** @var \Phalcon\Config $config */
$config = null;

/** @var \PhalconRest\Api $app */
$app = null;

/** @var \PhalconApi\Http\Response $response */
$response = null;

try {
    define('ROOT_DIR', __DIR__ . '/..');
    define('APP_DIR', ROOT_DIR . '/app');
    define('VENDOR_DIR', ROOT_DIR . '/vendor');
    define('CONFIG_DIR', APP_DIR . '/configs');
    define('CERTIFICATE_DIR', APP_DIR . '/certificate');

    if (!function_exists('is_iterable')) {
        function is_iterable($var)
        {
            return is_array($var) || $var instanceof \Traversable;
        }
    }

    define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'development');

    // Autoload dependencies
    require VENDOR_DIR . '/autoload.php';

    $loader = new \Phalcon\Loader();

    $loader->registerNamespaces([
        'App' => APP_DIR . '/library/App'
    ]);

    $loader->registerDirs([
        APP_DIR . '/views/'
    ]);

    $loader->register();

    // Config
    $configPath = CONFIG_DIR . '/default.php';

    if (!is_readable($configPath)) {
        throw new Exception('Unable to read config from ' . $configPath);
    }

    $config = new Phalcon\Config(include_once $configPath);

    $envConfigPath = CONFIG_DIR . '/server.' . APPLICATION_ENV . '.php';

    if (!is_readable($envConfigPath)) {
        throw new Exception('Unable to read config from ' . $envConfigPath);
    }

    $override = new Phalcon\Config(include_once $envConfigPath);

    $config = $config->merge($override);


    // Instantiate application & DI
    $di = new PhalconRest\Di\FactoryDefault();
    $app = new PhalconRest\Api($di);

    // Bootstrap components
    $bootstrap = new App\Bootstrap(
        new App\Bootstrap\ServiceBootstrap,
        new App\Bootstrap\MiddlewareBootstrap,
        new App\Bootstrap\CollectionBootstrap,
        new App\Bootstrap\RouteBootstrap,
        new App\Bootstrap\AclBootstrap
    );

    $bootstrap->run($app, $di, $config);

    // Start application
    $app->handle();

    // Set appropriate response value
    $response = $app->di->getShared(App\Constants\Services::RESPONSE);
    $response->setHeader('Access-Control-Allow-Origin', '*');

    $returnedValue = $app->getReturnedValue();

    if ($returnedValue !== null) {
        if (is_string($returnedValue)) {
            $response->setContent($returnedValue);
        } else {
            $response->setJsonContent($returnedValue, JSON_UNESCAPED_UNICODE);
        }
    }
} catch (\Exception $e) {
    // Handle exceptions
    $di = $app && $app->di ? $app->di : new PhalconRest\Di\FactoryDefault();
    /** @var \Phalcon\Http\Response $response */
    $response = $di->getShared(App\Constants\Services::RESPONSE);
    if (!$response || !$response instanceof PhalconApi\Http\Response) {
        $response = new PhalconApi\Http\Response();
    }

    $response->setHeader('Access-Control-Allow-Origin', '*');

    $debugMode = $config->debug ?? APPLICATION_ENV === 'development';

    $response->setErrorContent($e, $debugMode);
}
finally {

    // Send response
    if (!$response->isSent()) {
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Vary', 'Origin');
        $response->send();
    }
}
