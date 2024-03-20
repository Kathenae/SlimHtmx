<?php

namespace App\Controllers;

use App\Classes\Datastore;
use App\Classes\GET;
use App\Classes\POST;
use App\Classes\Route;
use App\Classes\SimpleApp;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ExamplesController
{
   protected $view;

   public function __construct(ContainerInterface $container)
   {
      $this->view = $container->get('view');
   }

   #[Route('/click-to-edit', 'GET|POST')]
   public function clickToEdit(Request $request, Response $response, array $args)
   {
      $user = new Datastore('click-to-edit.json');

      if ($request->isPost()) {
         $data = $request->getParsedBody();
         $user->set('firstName', $data['firstName']);
         $user->set('lastName', $data['lastName']);
         $user->set('email', $data['email']);
         $user->save();
         return $response->withAddedHeader('HX-Location', json_encode(['path' => '/click-to-edit', "target" => "#content"]));
      }

      return SimpleApp::render($response, 'examples/click-to-edit.phtml', [
         'user' => $user
      ]);
   }

   #[GET('/bulk-update')]
   #[POST('/users')]
   public function bulkUpdate(Request $request, Response $response, array $args)
   {
      $users = new Datastore('bulk-update.json');

      $activatedCount = 0;
      $deactivatedCount = 0;

      $message = null;
      if ($request->isPost()) {
         $data = $request->getParsedBody();
         foreach ($users as $user) {
            $id = $user['id'];
            $hasBeenActivated = isset ($data[$id]);
            if ($hasBeenActivated) {
               if (!$user['active']) {
                  $user['active'] = true;
                  $activatedCount += 1;
               }
            } else if ($user['active'] == true) {
               $user['active'] = false;
               $deactivatedCount += 1;
            }

            $users->set($id, $user);
         }

         $message = "Activated $activatedCount and deactivated $deactivatedCount users";
         $users->save();
      }


      return SimpleApp::render($response, 'examples/bulk-update.phtml', [
         'users' => $users,
         'message' => $message
      ]);
   }

   #[Route('/inline-validation', 'GET|POST')]
   public function inlineValidation(Request $request, Response $response, array $args)
   {
      $data = [];
      $body = $request->getParsedBody();
      $data['email'] = $body['email'] ?? '';
      $data['password'] = $body['password'] ?? '';

      if ($request->isPost()) {
         // Validate Email
         if (empty ($data['email'])) {
            $data['errors']['email'] = 'Email is required';
         } else if (strlen($data['email']) > 255) {
            $data['errors']['email'] = 'Email length should not exceed 255 characters';
         } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['errors']['email'] = 'Email is invalid';
         }
         
         // Validate password
         if (empty ($data['password'])) {
            $data['errors']['password'] = 'Password is required';
         }
         else if (preg_match('/[[:upper:]]/', $data['password']) != 1) {
            $data['errors']['password'] = "Password must contain uppercase characters";
         } else if (strlen($data['password']) < 8) {
            $data['errors']['password'] = "Password must contain at least 8 characters";
         } else if (preg_match('/\d/', $data['password']) != 1) {
            $data['errors']['password'] = 'Password must contain digits';
         }
      }

      return SimpleApp::render($response, 'examples/inline-validation.phtml', $data);
   }
}