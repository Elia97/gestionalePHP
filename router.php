<?php
class Router
{
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function addRoute($path, $callback)
    {
        $this->routes[$path] = $callback;
    }

    public function route()
    {
        $requestUri = $_SERVER['REQUEST_URI'];

        // Rimuovi query string se presente
        $requestPath = strtok($requestUri, '?');

        // Rimuovi il base path se presente
        if ($this->basePath && strpos($requestPath, $this->basePath) === 0) {
            $requestPath = substr($requestPath, strlen($this->basePath));
        }

        // Se il path è vuoto, imposta come home
        if (empty($requestPath) || $requestPath === '/') {
            $requestPath = '/';
        }

        // Cerca la route corrispondente
        if (array_key_exists($requestPath, $this->routes)) {
            $callback = $this->routes[$requestPath];

            if (is_callable($callback)) {
                call_user_func($callback);
            } elseif (is_string($callback)) {
                include $callback;
            }
        } else {
            // Pagina 404
            http_response_code(404);
            include 'pages/404.php';
        }
    }

    public function getCurrentPath()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestPath = strtok($requestUri, '?');

        if ($this->basePath && strpos($requestPath, $this->basePath) === 0) {
            $requestPath = substr($requestPath, strlen($this->basePath));
        }

        return empty($requestPath) || $requestPath === '/' ? '/' : $requestPath;
    }
}
