<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;

class ApiDocumentationController extends Controller
{
    public function index()
    {
        $routeCollection = Route::getRoutes();
        $apiRoutes = [];

        // Process all routes to find API endpoints
        foreach ($routeCollection as $route) {
            $prefix = $route->getPrefix();
            if (str_starts_with($prefix, 'api')) {
                $methods = $route->methods();
                $uri = $route->uri();
                $name = $route->getName();
                $actionName = $route->getActionName();
                
                // Extract controller and method information
                $controllerMethod = $this->extractControllerMethod($actionName);
                
                // Get documentation from method comments
                $docComment = $this->getMethodDocComment($actionName);
                
                $apiRoutes[] = [
                    'methods' => $methods,
                    'uri' => $uri,
                    'name' => $name,
                    'controller' => $controllerMethod['controller'] ?? 'Unknown',
                    'method' => $controllerMethod['method'] ?? 'Unknown',
                    'description' => $this->parseDocComment($docComment),
                    'parameters' => $this->parseParameters($docComment),
                    'responses' => $this->parseResponses($docComment),
                ];
            }
        }

        return view('api.documentation', [
            'routes' => $apiRoutes
        ]);
    }

    /**
     * Extract controller and method name from action string
     */
    private function extractControllerMethod($actionName)
    {
        if ($actionName === 'Closure') {
            return [
                'controller' => 'Closure',
                'method' => null,
            ];
        }
        
        $parts = explode('@', $actionName);
        
        if (count($parts) === 2) {
            return [
                'controller' => $parts[0],
                'method' => $parts[1],
            ];
        }
        
        return [
            'controller' => $actionName,
            'method' => null,
        ];
    }

    /**
     * Get PHPDoc comments from controller method
     */
    private function getMethodDocComment($actionName)
    {
        if ($actionName === 'Closure') {
            return null;
        }
        
        $parts = explode('@', $actionName);
        
        if (count($parts) !== 2) {
            return null;
        }
        
        $controllerClass = $parts[0];
        $method = $parts[1];
        
        try {
            $reflectionClass = new ReflectionClass($controllerClass);
            $reflectionMethod = $reflectionClass->getMethod($method);
            
            return $reflectionMethod->getDocComment();
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    /**
     * Parse description from PHPDoc comments
     */
    private function parseDocComment($docComment)
    {
        if (!$docComment) {
            return 'No description available';
        }
        
        $docComment = preg_replace('/^\s*\/\*+\s*|\s*\*+\/\s*$/', '', $docComment);
        $lines = preg_split('/\r?\n/', $docComment);
        
        $description = '';
        foreach ($lines as $line) {
            $line = preg_replace('/^\s*\*\s*/', '', $line);
            
            if (preg_match('/^@.+$/', $line)) {
                continue;
            }
            
            $description .= ' ' . $line;
        }
        
        return trim($description);
    }

    /**
     * Parse parameter information from PHPDoc comments
     */
    private function parseParameters($docComment)
    {
        if (!$docComment) {
            return [];
        }
        
        $params = [];
        $matches = [];
        preg_match_all('/@param\s+([^\s]+)\s+\$([^\s]+)\s*([^\n]+)?/', $docComment, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $params[] = [
                'type' => $match[1],
                'name' => $match[2],
                'description' => trim($match[3] ?? ''),
            ];
        }
        
        return $params;
    }

    /**
     * Parse response codes from PHPDoc comments
     */
    private function parseResponses($docComment)
    {
        if (!$docComment) {
            return [];
        }
        
        $responses = [];
        $matches = [];
        preg_match_all('/@response\s+(\d+)\s+([^\n]+)?/', $docComment, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $responses[] = [
                'code' => $match[1],
                'description' => trim($match[2] ?? ''),
            ];
        }
        
        return $responses;
    }
}
