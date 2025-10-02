<?php


namespace Solobea\Helpers\helper;


class Config
{
    public static array $ORG=[
        "name"=>"Shule",
        "description"=>"Hello word this is the sample desc",
        "box"=>"P. O. Box 123",
        "address"=>"Tabora, Tanzania",
        "logo"=>"https://software.mwondoko.com/images/solobea_no_bg.png",
        "email"=>"email@gmail.com",
        "phone"=>"+255 123 123 1213",
    ];
    public static array $APP=[
        "channel"=>"channel_name",
        "api_key"=>"API Keys Here",
        "sms_sender"=> "0629077526",
    ];
    public static array $DB=[
        "db_name"=>"shule",
        "db_host"=>"localhost",
        "db_user"=> "root",
        "db_pass"=> "",
    ];
}