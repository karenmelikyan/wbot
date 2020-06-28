<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wbot\CommandsHandler;
use App\ClientModel;

class WBotController extends Controller
{
    public function webhook()
    {
        $apiUrl = 'https://eu77.chat-api.com/instance141030/';
        $token  = '15rk80ozte9orh81';
              
        /** Waiting for client`s command */
        $json = file_get_contents('php://input');
              
        /** Turn on commands handling */
        (new CommandsHandler($apiUrl, $token))->run($json);
    }

    /**
     * 
     */
    public function welcome()
    {
        $client = new ClientModel();
        
        $data = $client->getClientByPhone('+37477424845');

        echo $data['name'];
        
    }

}
