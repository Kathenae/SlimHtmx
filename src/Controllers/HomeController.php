<?php

namespace App\Controllers;

use App\Classes\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController {
   protected $view;
   
   public function __construct(ContainerInterface $container){
      $this->view = $container->get('view');
   }
   
   #[Route('/', 'GET')]
   public function home(RequestInterface $request, ResponseInterface $response, array $args){
      return $this->view->render($response, 'home.phtml');
   }
}