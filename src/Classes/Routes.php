<?php

namespace App\Classes;

use Attribute;

#[Attribute]
class Route
{
   public function __construct(
      public string $path,
      public mixed $method = null,
   ) {}
}

#[Attribute]
class GET extends Route
{
}

#[Attribute]
class POST extends Route
{
}

#[Attribute]
class PUT extends Route
{
}

#[Attribute]
class DELETE extends Route
{
}

#[Attribute]
class OPTIONS extends Route
{
}

class Routes
{
   static function resolve(\Slim\App $app)
   {
      $controllersDir = 'src/Controllers';
      $files = array_filter(scandir($controllersDir), fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'php');

      foreach ($files as $phpFile) {
         $className = 'App\\Controllers\\' . pathinfo($phpFile, PATHINFO_FILENAME);
         $reflectionClass = new \ReflectionClass($className);
         $reflectionMethods = $reflectionClass->getMethods();
         foreach ($reflectionMethods as $reflectionMethod) {
            $methodName = $reflectionMethod->name;
            $routeAttributes = $reflectionMethod->getAttributes();
            foreach ($routeAttributes as $routeAttribute) {
               $route = $routeAttribute->newInstance();
               $ar = explode('\\', $routeAttribute->getName());
               $attributeName = end($ar);
               $httpMethod = null;
               if (in_array($attributeName, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'])) {
                  $httpMethod = $attributeName;
                  $result = call_user_func_array([$app, $httpMethod], [$route->path, "$className:$methodName"]);
               } else if ($attributeName == 'Route') {
                  $httpMethods = explode('|', $route->method);
                  foreach ($httpMethods as $httpMethod) {
                     $result = call_user_func_array([$app, $httpMethod], [$route->path, "$className:$methodName"]);
                  }
               }
               
               $result->setName($methodName);
            }
         }
      }
   }
}