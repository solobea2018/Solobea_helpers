<?php

namespace Solobea\Helpers\data;
use Solobea\SchoolResult\database\Database;
use Solobea\SchoolResult\errors\Logger;

class DataVerifier
{
    public function verify_student_data($student): bool
    {
        $index_number=trim($student['index_number']);
        $combination=trim($student['combination']);
        $first_name=trim($student['first_name']);
        $last_name=trim($student['last_name']);
        $db=new Database();
        $school_number=explode(".",$index_number)[0];
        if ($combination==='' || $first_name===''|| $last_name==='' || $index_number===''){
            Logger::log("index","Null or empty at". $index_number);
            return false;
        }
        if (!$db->exists_school(ucfirst($school_number))){
             Logger::log("index",ucfirst($school_number)." school not exist");
            return false;
        }
        $pattern = "/^[SP]\d{4}\.\d{4}\.\d{4}$/i";// 'i' flag makes it case-insensitive

        if (!preg_match($pattern, $index_number)) {
            Logger::log("index",$index_number." Invalid index number");
            return false;
        }
        return true;
    }

    public function verify_result_data(array $result): bool
    {
        $index_number = trim($result['index_number']);
        $marks = trim($result['marks']);
        $pattern = "/^[SP]\d{4}\.\d{4}\.\d{4}$/i"; // 'i' flag makes it case-insensitive

        // Validate index number
        if (!preg_match($pattern, $index_number)) {
            Logger::log("index", $index_number . " Invalid index number");
            return false;
        }

        // Validate marks (between 0 and 100 inclusively)
        if (!is_numeric($marks) || $marks < 0 || $marks > 100) {
            Logger::log("marks", $marks . " Marks must be between 0 and 100 inclusively");
            return false;
        }

        return true;
    }
}