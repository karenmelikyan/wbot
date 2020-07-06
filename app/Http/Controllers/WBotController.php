<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wbot\CommandsHandler;
use App\MenuModel;
use App\ClientModel;

class WBotController extends Controller
{   
    public function webhook(Request $request)
    { 
        $json = file_get_contents('php://input');
        (new CommandsHandler(new MenuModel(), new ClientModel()))->run($json);
    }

}


