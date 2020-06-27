<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wbot\CommandsHandler;

class WBotController extends Controller
{
    /**
     * 
     */
    public function webhook()
    {
        echo 'webhook';

        $apiUrl = 'https://eu77.chat-api.com/instance141030/';
        $token  = '15rk80ozte9orh81';
        
        /** Waiting for clients command */
        $json = file_get_contents('php://input');
        
        /** Turn on commands handling */
        (new CommandsHandler($apiUrl, $token))->run($json);
    }

    /**
     * 
     */
    public function sender()
    {
    
    
    }

}
