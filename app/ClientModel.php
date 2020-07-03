<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ClientModel extends Model
{
    protected $attributes = [
        'welcome_status' => false,
        'welcome_amount' => 0,
    ];

    /**
     * 
     */
    public function __construct()
    {
        $this->setTable('clients');
    }

    /**
     * 
     */
    public function deleteOne(int $id): int
    {
        return $this->find($id)->delete();
    }

    /**
     * 
     */
    public function addOne(Request $request): bool
    {
        $dataArr['name'] = $request['name'];
        $dataArr['last_name'] = $request['last_name'];
        $dataArr['phone'] = $request['phone'];
        $dataArr['welcome_amount'] = 0;

        return $this->insert($dataArr);
    }

    /**
     * 
     */
    public function updateOne(Request $request): ?array
    {
        $dataArr['id'] = $request['id'];
        $dataArr['name'] = $request['name'];
        $dataArr['last_name'] = $request['last_name'];
        $dataArr['phone'] = $request['phone'];

        //data about sent messages shouldn't 
        //change & must be save as statistic data
        $client = new ClientModel();
        $oldData = $client->getOneById($request['id']);
        $dataArr['welcome_amount'] = $oldData['welcome_amount'];

        if($this->where('id', $request['id'])->update($dataArr)){
            return $dataArr;
        }

        return null;
    }

    /**
     * 
     */
    public function getOneById(int $id): ?array
    {
        $data = $this->where('id', $id)->get();
        $client = [];

        if($data){
            foreach($data as $item){
                $client['id'] = $item['id'];
                $client['name']  = $item['name'];
                $client['last_name'] = $item['last_name'];
                $client['phone'] = $item['phone'];
                $client['welcome_amount'] = $item['welcome_amount'];
     
                return $client;
            }
        }

        return null;
    }

     /**
     * 
     */
    public function getOneByPhone(int $phone): ?array
    {
        $data = $this->where('id', $phone)->get();
        $client = [];

        if($data){
            foreach($data as $item){
                $client['id'] = $item['id'];
                $client['name']  = $item['name'];
                $client['last_name'] = $item['last_name'];
                $client['phone'] = $item['phone'];
                $client['welcome_amount'] = $item['welcome_amount'];
     
                return $client;
            }
        }

        return null;
    }

    /**
     * 
     */
    public function getAllData(): ?array
    {
        $data = $this->all();
        $clientsArr = [];
        $index = 0;

        foreach($data as $item){
            $clientsArr[$index]['id'] = $item['id'];
            $clientsArr[$index]['name'] = $item['name'];
            $clientsArr[$index]['last_name'] = $item['last_name'];
            $clientsArr[$index]['phone'] = $item['phone'];
            $clientsArr[$index]['welcome_amount'] = $item['welcome_amount'];
            $index ++;
        }

        return is_array($clientsArr) ? $clientsArr : null;
    }
}
