<?php

namespace App\Wbot;
use App\MenuModel;

class CommandsHandler
{
    private string $apiUrl;
    private string $token;

    public function __construct($apiUrl, $token)
    {
       $this->apiUrl = $apiUrl;
       $this->token  = $token;
    }
   
    public function run($json)
    {
        if($chatId = $this->getChatId($json)){
            if($json = $this->getLastMessages($chatId, 10)){
                if($menuHandler = $this->getMenuHandler($json)){
                    if($command = $this->getLastCommand($json)){
                        // invoke appropriate method by 
                        // value in variable `$menuIdentif`
                        $this->$menuHandler($chatId, $command); 
                    }
                }
            }
        }
    }

    /*******************
    * Handler methods
    ********************/
    private function mainMenuHandler($chatId, $command)
    {
        switch($command)
        {
            case '0': $this->sendMenu($chatId, 'Главное меню');
            break;
            case '1': $this->sendMenu($chatId, 'Выберите свободный день');
            break;
            case '2': $this->sendMenu($chatId, '');
            break;
            case '3': $this->sendMenu($chatId, '');
            break;   
        }
    }

    /**
    * 
    */
    private function freeDaysMenuHandler($chatId, $command)
    {
        switch($command)
        {
            case '1': ;
            break;
            case '2': ;
            break;
            case '3': ;
            break;
            case '4': ;
            break;
            case '5': ;
            break;
            case '6': ;
            break;
            case '7': ;
            break;
        }
    }

    /**
    * 
    */
    private function freeTimeMenuHandler($chatId, $command)
    {
        switch($command)
        {
            case '1': ;
            break;
            case '2': ;
            break;
            case '3': ;
            break;
        }
    }

    /**
    * 
    */
    private function cancelMenuHandler($chatId, $command)
    {
        switch($command)
        {
            case '1': ;
            break;
            case '2': ;
            break;
        }
    }

    /*******************
    * Service methods
    ********************/
    private function getMenuHandler($json): ?string
    {
        $decoded = json_decode($json, true);

        /** reverse array for find last sent menu */
        $decoded = array_reverse($decoded['messages']);

        /** search for menu`s keywords in array of messages */
        foreach($decoded as $item){
            if($item['fromMe']){//checking only messages sent from me
                if(strripos($item['body'], 'Главное меню')){
                    return 'mainMenuHandler';
                }
        
                if(strripos($item['body'], 'свободный день')){
                    return 'freeDaysMenuHandler';
                }
        
                if(strripos($item['body'], 'свободное время')){
                    return 'freeTimeMenuHandler';
                }
        
                if(strripos($item['body'], 'перенести тренировку')){
                    return 'cancelMenuHandler';
                }
            }
        }

        $this->error_log('Can`t get current menu');
        return null;
    }

    /**
    * 
    */
    private function getLastCommand($json): ?string
    {
        $decoded = json_decode($json, true);

        /** reverse array for find last sent command */
        $decoded = array_reverse($decoded['messages']);

        /** search for command in array of messages */
        foreach($decoded as $item){
            if(!$item['fromMe']){//checking only messages sent from client
                return $item['body'];
            }
        }

        $this->error_log('Can`t get last command');
        return null;
    }

    /**
    * 
    */
    private function getLastMessages($chatId, $limit): ?string
    {
        $url = $this->apiUrl . 'messages?token=' . $this->token . '&chatId=' . $chatId . '&limit=' . $limit;
        if($json = file_get_contents($url)){
            return $json;
        }

        $this->error_log('Can`t get last messages');
        return null;
    }

    /**
    * 
    */
    private function getChatId(string $json): ?string
    {
        $decoded = json_decode($json, true);

        if(isset($decoded['messages'])){
            foreach($decoded['messages'] as $message){
                if(!$message['fromMe']){// except cases when message recived from bot chatId.
                    return $message['chatId'];
                }
            }
        }

        $this->error_log('Can`t get chatId');
        return null;
    }

     /**
     * get menu from db and send it by chatId
     */
    public function sendMenu($chatId, $menuName)
    {
        $menu = new MenuModel();
        $menuText = $menu->getMenuByName($menuName);
    
        $this->sendMessage($chatId, $menuText);
    }

    /**
    * 
    */
    private function sendMessage(string $chatId, string $text): void
    {
        $data = [
            'chatId' => $chatId,
            'body' => $text,
        ];

        $this->sendRequest('message', $data);
    }

    /**
    * 
    */
    private function sendRequest(string $method, array $data): void
    {
        $url = $this->apiUrl . $method . '?token=' . $this->token;

        if(is_array($data)){ 
            $data = json_encode($data);
        }

        $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $data ]
            ]);

        $response = file_get_contents($url, false, $options);
        $this->response_log($response);
    }

    /**
    * 
    */
    private function checkWebhook(string $webhookUrl): void
    {
        $this->sendRequest('webhook', [
            'set' => true,
            'webhookUrl' => $webhookUrl,
        ]);
    }

    /**
    * 
    */
    public function sendWelcome($chatId, $clientName): void
    {
        $message = "Добрый день " . $clientName . ", меня зовут Варя, я секретарь и помощник Пети.
        Если вы не записаны на следующую тренировку, то можете это сделать командами.\n".

        "0 - Открыть главное меню\n".
        "1 - Запросить свободное время\n".
        "2 - Перенести время тренировки\n".
        "3 - Удалить все записи о будущих тренировках";

        $this->sendMessage($chatId, $message);
    }
     
    /*********************
    * Additional methods
    *********************/
    private function error_log($text){
       file_put_contents('error.log', $text . ' ' . date('l jS \of F Y h:i:s A'), FILE_APPEND);
    }

    private function request_log($text){
        file_put_contents('request.log', $text . ' ' . date('l jS \of F Y h:i:s A'), FILE_APPEND);
    }

    private function response_log($text){
        file_put_contents('response.log', $text . ' ' . date('l jS \of F Y h:i:s A'), FILE_APPEND);
    }

    private function debug($obj)
    {
        echo '<pre>';
        var_dump($obj);
        die();
    }

}

