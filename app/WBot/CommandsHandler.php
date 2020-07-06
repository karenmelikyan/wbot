<?php

namespace App\Wbot;

class CommandsHandler
{                
    private $apiUrl = 'https://eu148.chat-api.com/instance145052/';
    private $token  = 'vizyz14q056uegj5';
    private $client;
    private $menu;

    public function __construct($menu, $client)
    {
       $this->menu = $menu;
       $this->client = $client;
    }

    public function run($json)
    {
        if($chatId = $this->getChatId($json)){
            if($jsonLastMessages = $this->getLastMessages($chatId, 10)){
                if($menuHandler = $this->getMenuHandler($jsonLastMessages)){
                    if($command = $this->getLastCommand($jsonLastMessages)){
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
            case 'zero': $this->sendMenuByName($chatId, 'Главное меню');
            break;
            case '1': $this->sendMenuByName($chatId, 'Запросить свободное время');
            break;
            case '2': $this->sendMenuByName($chatId, 'Выберите подходящее время:');
            break;
            case '3': $this->sendMenuByName($chatId, '');
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
    private function areYouSure($chatId, $command)
    {
        switch($command)
        {
            case '1': ;
            break;
            case '2': ;
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
                if(strripos($item['body'], 'Открыть главное меню')){
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

        /** reverse array for find last sent menu */
        $decoded = array_reverse($decoded['messages']);

        /** search for command in array of messages */
        foreach($decoded as $item){
            if(!$item['fromMe']){//checking only messages sent from client
                $command = mb_strtolower($item['body']);
                return str_replace('0', 'zero', $command);// replace '0' to 'zero' cuz there may be problems                                                          
            }                                             // with compare data in if operator
        }

        $this->error_log('Can`t get last command');
        return null;
    }

    /**
    * 
    */
    private function getLastMessages($chatId, $limit): ?string
    {
        $url = $this->apiUrl . 'messages?token=' . $this->token . '&chatId=' . $chatId . '&last=' . $limit . '&limit=' . $limit;
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
    public function sendMenuByName($chatId, $menuName): void
    {
        //get phone from `chatId`
        $phone = str_replace('@c.us', '', $chatId);

        if($clientArr = $this->client->getOneByPhone($phone))
        {
            //get main menu from db
            $menuArr = $this->menu->getMenuByName($menuName);

            //replace # on client name in menu text
            $menuText = str_replace('#', $clientArr['name'], $menuArr['text']);

            //remove `+` symbol if exist in phone nomber & concatenate chat id suffix
            $chatId = str_replace('+', '', $clientArr['phone'] . '@c.us');

            //send  main menu as welcome message
            $this->sendMessage($chatId, $menuText);
        }

    }

    /**
    * 
    */
    public function sendMessage(string $chatId, string $text): void
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
        // $this->sendRequest('webhook', [
        //     'set' => true,
        //     'webhookUrl' => $webhookUrl,
        // ]);
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

