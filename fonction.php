<?php

function ping($client, $i, $nbc, $cmd){
	server_send($client, $i, "serv: ok");
}

function client_send($client, $em, $nbc, $cmd){
    $dest = $cmd[1];
    $msg = $cmd[2];
    if (!is_numeric($dest)){
        for ($i = 0; isset($client[$i]); $i++){
            if (isset($client[$i][2]) && $client[$i][2] == $dest){
                server_send($client, $i, "msg: ".$client[$em][2].": $msg");
                return 0;
            }
        }
        server_send($client, $em, "$dest: not connected");
    }else if (isset($client[$dest][0])) 
        server_send($client, $dest, "msg: ". (isset($client[$em][2]) ? $client[$em][2] : $em) .": $msg");
    else server_send($client, $em, "$dest: not connected");
}

function connected($client, $a, $nbc, $cmd){
    for ($i = 0; $i != $nbc; $i++)
        if (socket_write($client[$i][0], "ping: ".$msg."\n\r", $nb+8))
            $connected = $connected .":".$i;
    server_send($client, $a, $connected);      
}

function send_all($client, $a, $nbc,$cmd){
    if (isset($cmd[1]) && $cmd[1] != null){
        $msg = $cmd[1];
        for ($i = 0; $i != $nbc; $i++)
            server_send($client, $i, (isset($client[$a][2]) ? $client[$a][2] : $a). ": send_all: $msg");
    }else server_send($client, $a, "send_all: error: 1");
}

function quit($client, $i, $nbc, $cmd){
	server_send($client, $i, "Disconnect");
    socket_close($client[$i][0]);
    echo "$i: Disconnect\n";
}

function login(&$client, $i, $nbc, $cmd){
    if (isset($cmd[1]) && isset($cmd[2]) && $cmd[1] != null && $cmd[2] != null){
    	$login = $cmd[1];
    	$pass = $cmd[2];
        if ($login == $login && $pass == "a") {
            global $client;
            $client[$i][1] = 1;
            $client[$i][2] = $login;
            server_send($client, $i, "serv: authed");
            return $client;
        } else server_send($client, $i, "autherror");
    } else server_send($client, $i, "log: error: 1");
}

////
function register($login, $pass, $cpass){

    if (isset($cmd[3]) && $cmd[3] != null && $cmd[2] == $cmd[3])
        echo $login." ".$pass." ".$cpass;
    else server_send($client, $i, "reg: error: 1");
}

function client_stat(&$client, $i, $nbc, $cmd){
    print_r($stat);
    server_send($client, $i, $stat[$i][1]);   
}


