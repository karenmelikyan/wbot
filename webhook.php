<?php

// $json = file_get_contents('php://input');
// file_put_contents('test', $json, FILE_APPEND);

$json = 'My redirect test';

header("Location: /webhook?my_json=" . $json);


