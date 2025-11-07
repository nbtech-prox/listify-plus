<?php
class Todo {
    private $conn;
    private $table_name = "todos";

    public $id;
    public $title;
    public $description;
    public $completed;
    public $priority;
    public $created_at;
    public $updated_at;
    public $completed_at;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, description, priority, user_id, created_at) 
                  VALUES (:title, :description, :priority, :user_id, NOW())";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":priority", $this->priority);
        $stmt->bindParam(":user_id", $this->user_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getByUserId($user_id) {
        $query = "SELECT t.*, u.full_name, u.email, u.profile_image 
                  FROM " . $this->table_name . " t
                  LEFT JOIN users u ON t.user_id = u.id
                  WHERE t.user_id = :user_id 
                  ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    public function getAll() {
        $query = "SELECT t.*, u.full_name, u.email, u.profile_image 
                  FROM " . $this->table_name . " t
                  LEFT JOIN users u ON t.user_id = u.id
                  ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->completed = $row['completed'];
            $this->priority = $row['priority'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->completed_at = $row['completed_at'];
            $this->user_id = $row['user_id'];
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, 
                      description = :description, 
                      priority = :priority,
                      completed = :completed,
                      updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":priority", $this->priority);
        $stmt->bindParam(":completed", $this->completed);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function toggle() {
        $query = "UPDATE " . $this->table_name . " 
                  SET completed = NOT completed,
                      completed_at = CASE WHEN completed = 0 THEN NOW() ELSE NULL END,
                      updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function countCompleted() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE completed = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function countActive() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE completed = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
