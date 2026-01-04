<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $middlewares = [];
    protected $groupStack = [];

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function any($path, $handler)
    {
        $this->addRoute('ANY', $path, $handler);
    }

    public function group($attributes, $callback)
    {
        $this->groupStack[] = $attributes;
        call_user_func($callback, $this);
        array_pop($this->groupStack);
    }

    protected function addRoute($method, $path, $handler)
    {
        $prefix = '';
        $middleware = [];

        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= $group['prefix'];
            }
            if (isset($group['middleware'])) {
                $middleware = array_merge($middleware, (array) $group['middleware']);
            }
        }

        $path = '/' . trim($prefix . '/' . trim($path, '/'), '/');

        // Ensure root path is just /
        if ($path === '//') $path = '/';

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function dispatch($uri, $method)
    {
        // Simple base path stripping (if installed in subdirectory)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }
        $uri = '/' . trim(parse_url($uri, PHP_URL_PATH), '/');
        if ($uri === '//') $uri = '/';

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['path']);
            $pattern = "@^" . $pattern . "$@D";

            if (($route['method'] === $method || $route['method'] === 'ANY') && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match

                // Run Middleware
                foreach ($route['middleware'] as $mw) {
                    $instance = new $mw();
                    $res = $instance->handle();
                    if ($res === false) {
                        return; // Middleware stopped execution
                    }
                }

                $handler = $route['handler'];
                if (is_array($handler)) {
                    $controller = new $handler[0]();
                    return call_user_func_array([$controller, $handler[1]], $matches);
                }

                return call_user_func_array($handler, $matches);
            }
        }

        // 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
