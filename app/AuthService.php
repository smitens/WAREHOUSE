<?php

namespace Warehouse\App;

class AuthService
{
    private array $users;

    public function __construct(string $filePath)
    {
        $this->users = $this->loadUsers($filePath);
    }

    private function loadUsers(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Users file not found.");
        }

        $jsonContent = file_get_contents($filePath);

        if ($jsonContent === false) {
            throw new \Exception("Failed to read users file.");
        }

        $users = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON decode error: " . json_last_error_msg());
        }

        if (!is_array($users)) {
            throw new \Exception("Invalid users file format.");
        }

        return $users;
    }

    public function login (string $username, string $password): string
    {
        foreach ($this->users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                return "\033[32mLogin successful!\033[0m";
            }
        }

        throw new \Exception("\033[31mInvalid credentials.Proceed again!\033[0m");
    }
}
