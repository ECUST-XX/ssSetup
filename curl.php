<?php

header('Content-type: application/json; charset=UTF-8');

//登陆并获取cookie
function login($email,$password){
    $login_url='https://app.arukas.io/api/login';
    $postdata = array('email' =>$email ,'password'=>$password);
    $cookie_file = dirname(__FILE__).'/cookie.txt';
    $login=curl_init($login_url);
    curl_setopt($login,CURLOPT_HEADER,0);
    curl_setopt($login,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($login,CURLOPT_POST,1);
    curl_setopt($login,CURLOPT_POSTFIELDS,$postdata);
    //curl_setopt($login,CURLOPT_SSL_VERIFYPEER,false); //忽略SSL证书
    curl_setopt($login,CURLOPT_COOKIEJAR,$cookie_file);
    $api = curl_exec($login);
    curl_close($login);
    return $api;
}

//利用cookie获取信息
function getMyinfo(){
    $url='https://app.arukas.io/api/me';
    $cookie_file = dirname(__FILE__).'/cookie.txt';
    $curl=curl_init($url);
    curl_setopt($curl,CURLOPT_HEADER, 0);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
    $api=curl_exec($curl);
    curl_close($curl);
    return $api;
}

function getApps(){
    $url='https://app.arukas.io/api/apps';
    $cookie_file = dirname(__FILE__).'/cookie.txt';
    $curl=curl_init($url);
    curl_setopt($curl,CURLOPT_HEADER, 0);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
    $api=curl_exec($curl);
    curl_close($curl);
    return $api;
}

function getContainer(){

    $url='https://app.arukas.io/api/containers';

    $cookie_file = dirname(__FILE__).'/cookie.txt';
    $curl=curl_init($url);
    curl_setopt($curl,CURLOPT_HEADER, 0);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
    $api=curl_exec($curl);
    curl_close($curl);
    return $api;
}



function setApp($name){

    $postData = ["data"=>[
        0=>[
            "type"=>"containers",
            "attributes"=>[
                "image_name"=>"mo2017/shadowsocks",
                "instances"=> 1,
                "mem"=> 256,
                "cmd"=> "",
                "envs"=>[
                    0=>[
                        "key"=>"PASS",
                        "value"=> "601qaz106"
                    ]
                ],
                "ports"=>[
                    0=>[
                    "number"=> 8388,
                    "protocol"=> "tcp"
                ]
                ],
                "arukas_domain"=> ""
            ]
        ],
        1=>[
            "type"=>"apps",
            "attributes"=>[
                    "name"=> $name
            ]
        ]
    ],
    ];

    $postData = json_encode($postData);
    //php内置curl方法传参后，服务器应答为 500 服务器内部错误，故改用shell命令直接调用linux curl
//    $url='https://app.arukas.io/api/app-sets';
//    printf($postData);
//    $curl=curl_init($url);
//    curl_setopt($curl,CURLOPT_NETRC,1);
//   // curl_setopt(CURL *handle, CURLOPT_NETRC_FILE, char *file);
//    curl_setopt($curl,CURLOPT_HEADER,0);
//    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//    curl_setopt($curl,CURLOPT_POST,1);
//    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
//    curl_setopt($curl,CURLOPT_POSTFIELDS,$postData);
//    curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/vnd.api+json","Accept: application/vnd.api+json"]);
//    $api = curl_exec($curl);
//    curl_close($curl);


    //$shell 中不能有 \\ 回车 等符号 否则无法正常输出命令！！！
    $shell = "curl -n -X POST https://app.arukas.io/api/app-sets -d '$postData' -H \"Content-Type: application/vnd.api+json\" -H \"Accept: application/vnd.api+json\" ";
    //printf($shell);
    $api = shell_exec($shell);
    return $api;


}

function startApp($id){
    $url='https://app.arukas.io/api/containers/'.$id.'/power';

    $curl=curl_init($url);
    curl_setopt($curl,CURLOPT_NETRC,1);
    curl_setopt($curl,CURLOPT_HEADER,0);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_POST,1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/vnd.api+json","Accept: application/vnd.api+json"]);
    $api = curl_exec($curl);
    curl_close($curl);
    return $api;
}

function getPort(){
    $container = json_decode(getContainer(),true);
    //var_dump($container);

    foreach ($container["data"] as $item => $value){
        //var_dump($value);

        $service_port = $value["attributes"]["port_mappings"][0][0]["service_port"];
        $host = $value["attributes"]["port_mappings"][0][0]["host"];

        print($host."\n".$service_port."\n");
    }
}