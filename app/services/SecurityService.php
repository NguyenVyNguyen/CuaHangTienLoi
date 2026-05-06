<?php

// require_once __DIR__ . "/../repositories/EmployeeAccountRepository.php";
// require_once __DIR__ . "/../repositories/CustomerAccountRepository.php";

// class SecurityService
// {
//     private static $employeeAccountDB;
//     private static $customerAccountDB;

//     public static function init()
//     {
//         self::$employeeAccountDB = new EmployeeAccountRepository();
//         self::$customerAccountDB = new CustomerAccountRepository();
//     }

//     // ================= USER AUTH =================

//     public static function authorize($userType, $userName, $password)
//     {
//         if ($userType === "employee") {
//             return self::$employeeAccountDB->authorize($userName, $password);
//         }

//         return self::$customerAccountDB->authorize($userName, $password);
//     }

//     // ================= CHANGE PASSWORD =================

//     public static function changePassword($userType, $userName, $password)
//     {
//         if ($userType === "employee") {
//             return self::$employeeAccountDB->changePassword($userName, $password);
//         }

//         return self::$customerAccountDB->changePassword($userName, $password);
//     }
// }