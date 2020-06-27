<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MenuModel;

class AdminController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Show dashboard */
    public function index()
    {
        return view('home');
    }

    /** Show data from db */
    public function show()
    {
        $menu = new MenuModel();
        $menuArr = $menu->getMenuByName('Главное меню');
       
        return view('edit', [
            'menu' => $menuArr,
            'message' => null,
        ]);
    }

    /**
     * 
     */
    public function update(Request $request)
    {
        $menu = new MenuModel();

        $menuArr = $menu->getMenuByName('Главное меню');
        $menuArr['text'] = $request->menu;
        $menu->updateOneByName('Главное меню', $menuArr);
        $menuArr = $menu->getMenuByName('Главное меню');
       
        return view('edit', [
            'menu' => $menuArr,
            'message' => 'Данные обновлены',
        ]);
        
    }
    
}
