<?php

namespace App\Classes;

use App\Classes\HtmxRenderer;
use Psr\Http\Message\ResponseInterface;
use Slim\App as SlimApp;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use PDO;

class SimpleApp
{
   public static $app;
   private static Request $request;
   private static Response $response;
   
   public static function init()
   {

      $config['displayErrorDetails'] = true;
      $config['addContentLengthHeader'] = false;

      $config['db']['host'] = 'localhost';
      $config['db']['user'] = 'root';
      $config['db']['pass'] = 'password';
      $config['db']['dbname'] = 'slim_htmx';

      $app = new SlimApp(['settings' => $config]);
      self::registerContainers($app);
      self::registerMiddleware($app);

      Routes::resolve($app);
      self::$app = $app;
   }
   
   private static function registerContainers(SlimApp $app)
   {
      
      // Logger
      $container = $app->getContainer();
      $container['logger'] = function (ContainerInterface $c) {
         $logger = new Logger('app_logger');
         $fileHandler = new StreamHandler('logs/app.log');
         $logger->pushHandler($fileHandler);
         return $logger;
      };
      
      // Database
      $container['db'] = function ($c) {
         $db = $c['settings']['db'];
         $pdo = new PDO(
            'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
            $db['user'],
            $db['pass']
         );
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
         return $pdo;
      };
      
      // View
      $phpview = new HtmxRenderer('src/templates/');
      $phpview->setLayout('layout.phtml');
      $container['view'] = $phpview;
   }
   
   private static function registerMiddleware(SlimApp $app){
      $app->add(function(Request $request, Response $response, $next) {
         self::$request = $request;
         self::$response = $response;
         return $next($request, $response);
      });
   }
   
   public static function isHxRequest(){
      return self::$request->hasHeader('HX-REQUEST');
   }
   
   public static function hasQueryParam(string $param){
      $value = self::$request->getQueryParam($param);
      return $value !== null;
   }
   
   public static function run(){
      self::$app->run();
   }
   
   public static function getRoutes(): array {
      return self::$app->getContainer()->router->getRoutes();
   }
   
   public static function render(ResponseInterface $response, string $template, array $data = []) : ResponseInterface{
      return self::getRenderer()->render($response, $template, $data);
   }
   
   private static function getRenderer() : HtmxRenderer{
      return self::$app->getContainer()->view;
   }
}