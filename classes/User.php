<?php
require_once __DIR__ . '/../config/db_config.php';
class User
{
    private $db;
    public function __construct()
    {
        $this->db = $this->getDbConnection();
    }
    private function getDbConnection()
    {
        return connect_to_db();
    }
    public function register($username, $password, $firstName, $lastName)
    {
        $existingUser = $this->getUserByUsername($username);
        if ($existingUser) {
            return false;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, first_name, last_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashedPassword, $firstName, $lastName);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    private function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row;
    }
    public function login($username, $password)
    {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            return true;
        } else {
            return false;
        }
    }
    public function getUserInfo($userID)
    {
        $query = "SELECT username, first_name, last_name FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $userInfo = $result->fetch_assoc();
        return $userInfo;
    }
    public function getUserPosts($userID)
    {
        $sql = "SELECT * FROM posts WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $userID);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        $resultSet = $stmt->get_result();
        $posts = [];
        while ($row = $resultSet->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    }
    public function getAllPosts()
    {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        $resultSet = $stmt->get_result();
        $posts = [];
        while ($row = $resultSet->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    }
    public function addComment($post_id, $user_id, $content)
    {
        $query = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iis', $post_id, $user_id, $content);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    public function getCommentsForPost($post_id)
    {
        $query = "SELECT c.id, c.user_id, c.content, c.created_at, u.username, u.first_name, u.last_name
              FROM comments c
              INNER JOIN users u ON c.user_id = u.id
              WHERE c.post_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        return $comments;
    }
    public function deleteComment($comment_id)
    {
        $query = "DELETE FROM comments WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $comment_id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    public function editComment($comment_id, $content)
    {
        $query = "UPDATE comments SET content = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('si', $content, $comment_id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
