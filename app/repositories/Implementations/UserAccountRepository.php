<?php

require_once __DIR__ . "/../Interfaces/IUserAccountRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class UserAccountRepository implements IUserAccountRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function authorize($userName, $password)
    {
        $sql = "
            SELECT UserId, UserName, DisplayName, Email, Photo, RoleNames
            FROM Users
            WHERE UserName = :username AND Password = :password
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "username" => $userName,
            "password" => $password
        ]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function changePassword($userName, $password)
    {
        $sql = "
            UPDATE Users
            SET Password = :password
            WHERE UserName = :username
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "username" => $userName,
            "password" => $password
        ]);
    }
}