<?php

class UserMeta
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addMeta($userId, $metaKey, $metaValue)
    {
        $stmt = $this->db->prepare("INSERT INTO usermeta (user_id, meta_key, meta_value) VALUES (:user_id, :meta_key, :meta_value)");
        $stmt->execute([
            'user_id' => $userId,
            'meta_key' => $metaKey,
            'meta_value' => $metaValue,
        ]);
    }

    public function getMeta($userId, $metaKey)
    {
        $stmt = $this->db->prepare("SELECT meta_value FROM usermeta WHERE user_id = :user_id AND meta_key = :meta_key");
        $stmt->execute(['user_id' => $userId, 'meta_key' => $metaKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['meta_value'] ?? null;
    }
}
