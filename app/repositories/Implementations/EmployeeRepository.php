<?php

require_once __DIR__ . "/../Interfaces/IEmployeeRepository.php";
require_once __DIR__ . "/../../database/Database.php";

class EmployeeRepository implements IEmployeeRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    // =========================
    // LIST
    // =========================
    public function list($input)
    {
        // FIX: đảm bảo luôn có đủ field
        $page = isset($input->page) ? (int)$input->page : 1;
        $pageSize = isset($input->pageSize) ? (int)$input->pageSize : 10;
        $searchValue = isset($input->searchValue) ? $input->searchValue : "";

        $offset = ($page - 1) * $pageSize;

        $result = [
            "page" => $page,
            "pageSize" => $pageSize,
            "total" => 0,
            "data" => []
        ];

        // ================= COUNT =================
        $sql = "SELECT COUNT(*) FROM Employees
            WHERE (:search = ''
            OR FullName LIKE :s
            OR Phone LIKE :s
            OR Email LIKE :s)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            "search" => $searchValue,
            "s" => "%" . $searchValue . "%"
        ]);

        $result["total"] = $stmt->fetchColumn();

        if ($result["total"] == 0) return $result;

        // ================= DATA =================
        $sql = "SELECT 
            EmployeeID, 
            FullName, 
            BirthDate, 
            Address, 
            Phone, 
            Email, 
            Photo, 
            IsWorking,
            RoleNames 
        FROM Employees
        WHERE (:search = ''
        OR FullName LIKE :s
        OR Phone LIKE :s
        OR Email LIKE :s)
        ORDER BY FullName
        LIMIT :offset, :pageSize";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":search", $searchValue);
        $stmt->bindValue(":s", "%" . $searchValue . "%");
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":pageSize", $pageSize, PDO::PARAM_INT);

        $stmt->execute();

        $result["data"] = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $result;
    }

    // =========================
    // GET
    // =========================
    public function get($id)
    {
        $sql = "SELECT * FROM Employees WHERE EmployeeID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // =========================
    // ADD
    // =========================
    public function add($data)
    {
        $sql = "INSERT INTO Employees
                (FullName, BirthDate, Address, Phone, Email, Photo, IsWorking)
                VALUES
                (:name, :birth, :address, :phone, :email, :photo, :working)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "name" => $data->FullName,
            "birth" => $data->BirthDate,
            "address" => $data->Address,
            "phone" => $data->Phone,
            "email" => $data->Email,
            "photo" => $data->Photo,
            "working" => $data->IsWorking
        ]);

        return $this->conn->lastInsertId();
    }

    // =========================
    // UPDATE
    // =========================
    public function update($data)
    {
        $sql = "UPDATE Employees SET
                FullName = :name,
                BirthDate = :birth,
                Address = :address,
                Phone = :phone,
                Email = :email,
                Photo = :photo,
                IsWorking = :working
                WHERE EmployeeID = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "name" => $data->FullName,
            "birth" => $data->BirthDate,
            "address" => $data->Address,
            "phone" => $data->Phone,
            "email" => $data->Email,
            "photo" => $data->Photo,
            "working" => $data->IsWorking,
            "id" => $data->EmployeeID
        ]);
    }

    // =========================
    // DELETE
    // =========================
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Employees WHERE EmployeeID = :id");
        return $stmt->execute(["id" => $id]);
    }

    // =========================
    // IS USED
    // =========================
    public function isUsed($id)
    {
        $sql = "SELECT COUNT(*) FROM Orders WHERE EmployeeID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    // =========================
    // VALIDATE EMAIL
    // =========================
    public function validateEmail($email, $id = 0)
    {
        if ($id == 0) {
            $sql = "SELECT COUNT(*) FROM Employees WHERE Email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["email" => $email]);
        } else {
            $sql = "SELECT COUNT(*) FROM Employees
                    WHERE Email = :email AND EmployeeID <> :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                "email" => $email,
                "id" => $id
            ]);
        }

        return $stmt->fetchColumn() == 0;
    }

    // Trong EmployeeRepository.php

    public function updatePassword($id, $hashedPassword)
    {
        $sql = "UPDATE Employees SET Password = :pw WHERE EmployeeID = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['pw' => $hashedPassword, 'id' => $id]);
    }

    public function updateRoles($id, $roleNames)
    {
        $sql = "UPDATE Employees SET RoleNames = :roles WHERE EmployeeID = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['roles' => $roleNames, 'id' => $id]);
    }
}
