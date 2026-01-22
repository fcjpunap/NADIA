<?php
class App {
    protected $controller = 'AuthController';
    protected $method = 'login'; // Método por defecto inicial
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Buscar Controlador
        if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . 'Controller.php')) {
            $this->controller = ucwords($url[0]) . 'Controller';
            // IMPORTANTE: Si cambiamos de controlador, el método por defecto debe ser index, NO login
            $this->method = 'index'; 
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Buscar Método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Obtener Parámetros
        $this->params = $url ? array_values($url) : [];

        // 4. EJECUCIÓN SEGURA
        // Verificamos si el método realmente existe antes de llamar
        if (method_exists($this->controller, $this->method)) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            // Si no existe (ej. DocenteController->login), mostrar error amigable o ir a dashboard
            if($this->controller instanceof AuthController) {
                call_user_func_array([$this->controller, 'login'], []);
            } else {
                // Fallback seguro
                if(method_exists($this->controller, 'dashboard')) {
                     call_user_func_array([$this->controller, 'dashboard'], []);
                } else {
                     echo "<h1>Error 404</h1><p>La página o acción solicitada no existe.</p>";
                }
            }
        }
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}
