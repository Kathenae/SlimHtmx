<?php

namespace App\Controllers;

use App\Classes\Datastore;
use App\GET;
use App\POST;
use App\Route;
use App\SimpleApp;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ExamplesController {
   protected $view;
   
   public function __construct(ContainerInterface $container){
      $this->view = $container->get('view');
   }

   #[Route('/click-to-edit', 'GET|POST')]
   public function clickToEdit(Request $request, Response $response, array $args)
   {
      $user = new Datastore('click-to-edit.json');
      
      if($request->isPost()){
         $data = $request->getParsedBody();
         $user->set('firstName', $data['firstName']);
         $user->set('lastName', $data['lastName']);
         $user->set('email', $data['email']);
         $user->save();
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
            $hasBeenActivated = isset($data[$id]);
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
}