<?php
require_once __DIR__ . '/../config/db_config.php';

class Blog
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

    public function createPost($userID, $title, $content)
    {
        $db = $this->db;
        $sql = "INSERT INTO posts (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("iss", $userID, $title, $content);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        return $stmt->insert_id;
    }

    public function deletePost($postId)
    {
        $db = $this->db;
        $sql = "DELETE FROM posts WHERE id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $postId);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getPost($postID)
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        return $post;
    }

    public function editPost($postID, $title, $content)
    {
        $db = $this->db;
        $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ssi", $title, $content, $postID);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
