<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MenuModel;
use App\ClientModel;

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

    public function deleteClient(Request $request)
    {
        $client = new ClientModel();
        $client->deleteOne($request['id']);

        //update admin panel after item delete
        return $this->showAllClients();
    }

    /** update client`s data */
    public function updateClient(Request $request)
    {
        $client = new ClientModel();
        if($dataArr = $client->updateOne($request)){
            return view('updateClient', [
                'client'  => $dataArr,
                'message' => 'Даные клиента обновлены!',
            ]);
        }

        return view('updateClient', [
            'client'  => $client->getOneById($request['id']),
            'message' => 'Что то пошло не так! Попробуйте ещё',
        ]);
    }

    /** Show clients data from db */
    public function showUpdateClient(Request $request)
    {
        $client = new ClientModel();
        $client = $client->getOneById($request['id']);

        return view('updateClient', [
            'client'  => $client,
            'message' => null,
        ]);
    }

    /** just show form of client`s add  */
    public function showAddClient()
    {
       return view('addClient', [
           'message' => null,
       ]);
    }

    /** add client to db */
    public function addClient(Request $request)
    {
        $client = new ClientModel();

        if($client->addOne($request)){
            return view('addClient', [
                'message' => 'Новый клиент добавлен',
            ]);
        }
        
        return view('addClient', [
            'message' => 'Что то пошло не так! Попробуйте ещё',
        ]);
    }

    /** Show all exists clients */
    public function showAllClients()
    {
        $client = new ClientModel();
        $clientsArr = $client->getAllData();

        if(is_array($clientsArr)){
            return view('allClients', [
                'clients' => $clientsArr,
                'message' => null,
            ]);
        }
            
        return view('allClients', [
            'clients' => null,
            'message' => 'Нет клиентов в Вашей базе',
        ]);
    }

    /** Show menu data from db */
    public function showMenu()
    {
        $menu = new MenuModel();
        $menuArr = $menu->getMenuByName('Главное меню');
       
        return view('editMenu', [
            'menu' => $menuArr,
            'message' => null,
        ]);
    }

    /** update menu data */
    public function updateMenu(Request $request)
    {
        $menu = new MenuModel();

        $menuArr = $menu->getMenuByName('Главное меню');
        $menuArr['text'] = $request->menu;
        $menu->updateOneByName('Главное меню', $menuArr);
        $menuArr = $menu->getMenuByName('Главное меню');
       
        return view('editMenu', [
            'menu' => $menuArr,
            'message' => 'Данные обновлены',
        ]);
    }

}
