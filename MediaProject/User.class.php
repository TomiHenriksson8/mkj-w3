<?php 

namespace MediaProject;

class User {
    private int $user_id;
    private string $username;
    private string $password;
    private string $email;
    private int $user_level_id;
    private \DateTime $created_at;

    
    public function __construct($data) {
        $this->user_id = $data['user_id'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->email = $data['email'];
        $this->user_level_id = $data['user_level_id'];
        $this->created_at = new \DateTime($data['created_at']);
    }

    public function getUser(): array {
        return [
            'user_id' => $this->user_id,
            'username' => $this->username,
            'email' => $this->email,
            'user_level_id' => $this->user_level_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];

    }
}


?>