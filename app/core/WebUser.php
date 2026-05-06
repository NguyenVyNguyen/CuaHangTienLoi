<?php

class WebUser
{
    public ?string $userId = null;
    public ?string $userName = null;
    public ?string $displayName = null;
    public ?string $email = null;
    public ?string $photo = null;
    public array $roles = [];

    public function __construct(array $data = [])
    {
        $this->userId = $data['userId'] ?? null;
        $this->userName = $data['userName'] ?? null;
        $this->displayName = $data['displayName'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->photo = $data['photo'] ?? null;
        $this->roles = $data['roles'] ?? [];
    }

    // Lưu vào session
    public function saveToSession(): void
    {
        $_SESSION['auth_user'] = [
            'userId' => $this->userId,
            'userName' => $this->userName,
            'displayName' => $this->displayName,
            'email' => $this->email,
            'photo' => $this->photo,
            'roles' => $this->roles
        ];
    }

    // Lấy user từ session
    public static function current(): ?self
    {
        if (!isset($_SESSION['auth_user'])) {
            return null;
        }

        return new self($_SESSION['auth_user']);
    }

    // Kiểm tra role
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    // Logout
    public static function logout(): void
    {
        unset($_SESSION['auth_user']);
    }
}