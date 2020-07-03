<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wbot\CommandsHandler;

class WBotController extends Controller
{
    public function webhook(Request $request)
    {
        echo $request['my_json'];
    }

}
