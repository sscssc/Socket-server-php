<?php
include "fonction.php";

error_reporting(0);
shell_exec("cls");

function server_init(){
    $ip =  "5.135.181.181";//gethostbyname(trim(`hostname`));
    $port = "9099";

    if ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) echo "[Start] Server: $ip:$port\n";
    else echo "[Error] Server: not init...\n";

    socket_bind($sock, $ip, $port);
    socket_listen($sock);
    socket_set_nonblock($sock);

    return $sock;
}

function server_nclient(&$nbc, $sock, &$client){
    if ($newsock = @socket_accept($sock)) {
        if (is_resource($newsock)){ 
            echo "New client connected $nbc\n"; 
        
            $client[$nbc][0] = $newsock;
        } 
    }
    return $client;
}

function server_recv($nbc, $client){
    $fonction = array("send_all", "client_send", "ping", "quit", "register", "login");
    $commands = array("sall", "whisp", "ping", "exit", "reg", "log");
    for ($i = 0; $i != $nbc; $i++){
        if (isset($client[$i][0]) && $rep = socket_read($client[$i][0], 2048)){
            $rep = trim($rep, chr(13).chr(10));
            $rep = trim($rep, chr(10));
            $cmd = split(':', $rep);
            for ($z = 0; isset($commands[$z]); $z++) {
                if ($commands[$z] == $cmd[0]){
                    $fonction[$z]($client, $i, $nbc, $cmd); 
                    return 0;
                }
            }
            echo "[$i]: $rep\n";
            //server_send($client, $i, "cmd: $rep: not found");
        }
    }
}

function server_send($client, $i, $msg){
    $nb = strlen($msg);
    if (socket_write($client[$i][0], $msg."\n\r", $nb+8))
        echo "Sended to [$i] : $msg\n";
}

$sock = server_init();
$nbc = 0;
$client = array();

while (true) { 
    server_nclient($nbc, $sock, $client);
    $nbc = count($client);
    if ($nbc != 0) { 
        server_recv($nbc, $client);
    } 
    usleep(500); 
} 