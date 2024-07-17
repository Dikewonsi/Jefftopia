<?php

    namespace Framework;

    use App\Controllers\ErrorController;

    class Router {
        protected $routes = [];

        /**
         * Add a New Route
         *
         * @param string $method
         * @param string $uri
         * @param string $action
         * @return void
         */
        public function registerRoute($method, $uri, $action)
        {
            list($controller, $controllerMethod) = explode('@', $action);
            $this->routes[] = [
                'method' => $method,
                'uri' => $uri,
                'controller' => $controller,
                'controllerMethod' => $controllerMethod,
            ];
        }

        /**
         * ------GET route
         * 
         * @param string $uri
         * @param string $controller
         * @return void
         */
        public function get($uri, $controller){
           $this->registerRoute('GET', $uri, $controller);
        }

        /**
         * -------POST route
         * 
         * @param string $uri
         * @param string $controller
         * @return void
         */
        public function post($uri, $controller){
            $this->registerRoute('POST', $uri, $controller);
        }

        /**
         * --------PUT route
         * 
         * @param string $uri
         * @param string $controller
         * @return void
         */
        public function put($uri, $controller){
            $this->registerRoute('PUT', $uri, $controller);
        }

        /**
         * ---------DELETE route
         * 
         * @param string $uri
         * @param string $controller
         * @return void
         */
        public function delete($uri, $controller){
            $this->registerRoute('DELETE', $uri, $controller);
        }

       

        /**
         * Route the request METHOD
         * 
         * @param  string $uri
         * @param string $method
         * @return void
         */

         public function route($uri){
            $requestMethod = $_SERVER['REQUEST_METHOD'];

             // Check for _method input
            if ($requestMethod === 'POST' && isset($_POST['_method'])) {
                // Override the request method with the value of _method
                $requestMethod = strtoupper($_POST['_method']);
            }

            foreach($this->routes as $route)
            {
               // Split the current URI into segments
                $uriSegments = explode('/', trim($uri, '/'));

                // Split the route URI into segments
                $routeSegments = explode('/', trim($route['uri'], '/'));

                $match = true;

                // Check if the number of segments matches
                if (count($uriSegments) === count($routeSegments) && strtoupper($route['method'] === $requestMethod)) {
                    $params = [];

                    $match = true;

                    for ($i = 0; $i < count($uriSegments); $i++) {
                    // If the uri's do not match and there is no param
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }

                    // Check for the param and add to $params array
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                    }

                    if ($match) {
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    // Instatiate the controller and call the method
                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                    }
                }                
            }
            ErrorController::notFound();
         }
    }