<?php

namespace Macellan\LaravelJsRoutes\Generators;

use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;

class RoutesJavascriptGenerator
{

    /**
     * File system instance
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * Router instance
     * @var Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Clean routes
     * @var array
     */
    protected $routes;

    /**
     * Parsed routes
     * @var array
     */
    protected $parsedRoutes;

    public function __construct(File $file, Router $router)
    {
        $this->file = $file;
        $this->router = $router;
        $this->routes = $router->getRoutes();
    }


    /**
     * @param $path
     * @param $name
     * @param array $options
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function make($path, $name, array $options = [])
    {
        $options += ['prefix' => null];

        $this->parsedRoutes = $this->getParsedRoutes($options['prefix']);

        $template = $this->file->get(__DIR__ . '/templates/Router.js');

        $template = str_replace("routes: null,", 'routes: ' . json_encode($this->parsedRoutes) . ',', $template);
        $template = str_replace("'Router'", "'" . $options['object'] . "'", $template);

        if ($this->file->isWritable($path)) {
            $filename = $path . '/' . $name;
            return $this->file->put($filename, $template) !== false;
        }

        return false;
    }

    /**
     * Get parsed routes
     *
     * @param string $prefix
     * @return array
     */
    protected function getParsedRoutes($prefix = null)
    {
        $parsedRoutes = [];

        foreach ($this->routes as $route) {
            $routeInfo = $this->getRouteInformation($route);

            if ($routeInfo) {
                if ($prefix) {
                  $routeInfo['uri'] = $prefix . $routeInfo['uri'] ;
                }

                $parsedRoutes[] = $routeInfo;
            }
        }

        return array_filter($parsedRoutes);
    }

    /**
     * Get the route information for a given route.
     *
     * @param Route $route
     * @return array
     */
    protected function getRouteInformation(Route $route)
    {
        if ($route->getName()) {
            return [
                'uri'    => $route->uri(),
                'name'   => $route->getName(),
            ];
        }

        return null;
    }
}
