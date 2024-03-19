<?php

namespace App\Controllers;

use App\Route;
use App\SimpleApp;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;

class AuthController
{
   protected $view;

   public function __construct(ContainerInterface $container)
   {
      $this->view = $container->get('view');
   }

   #[Route('/sign-in', 'GET|POST')]
   public function signIn(Request $request, ResponseInterface $response, array $args)
   {
      $data = [];
      $body = $request->getParsedBody();
      $data['email'] = $body['email'] ?? '';
      $data['password'] = $body['password'] ?? '';
      
      if ($request->isPost()) {
         $data['errors']['email'] = $data['email'];
      }

      if ($request->isPost()) {
         if(preg_match('/[[:upper:]]/', $data['password']) != 1){
            $data['errors']['password'] = "Password must contain uppercase characters";
         }
         else if(strlen($data['password']) < 8){
            $data['errors']['password'] = "Password must contain at least 8 characters";
         }
         else if(preg_match('/\d/', $data['password']) != 1){
            $data['errors']['password'] = 'Password must contain digits';
         }
      }

      return SimpleApp::render($response, 'auth/sign-in.phtml', $data);
   }

   #[Route('/sign-up', 'GET|POST')]
   public function signUp(RequestInterface $request, ResponseInterface $response, array $args)
   {
      return $this->view->render($response, 'auth/sign-up.phtml');
   }
}