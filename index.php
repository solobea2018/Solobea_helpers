<?php

use Solobea\Helpers\data\Sanitizer;

require_once "vendor/autoload.php";

$str = "asilimia asira asisi";
if (Sanitizer::is_valid_message($str)){
    echo "Message is valid";
} else{
    echo "Message is invalid";
}