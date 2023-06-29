<?php

declare(strict_types=1);

class Task
{
    private $conn;
    private string $userEmail;

    public function __construct(Database $db, User $user)
    {
        $this->conn = $db->getConnection();
        $this->userEmail = $user->getUserEmail();
    }
    public function createTask(string $title, string $description): void
    {
        $db = $this->conn;
        $query = "INSERT INTO tasks(title,description,userId) VALUES('" . $title . "','" . $description . "','" . $this->userEmail . "');";
        $stmt = $db->prepare($query);

        try {
            $stmt->execute();
            echo json_encode(["msg" => "success"]);
        } catch (PDOException $e) {
            echo json_encode(["msg" => "Error:" . $e->getMessage()]);
        }
    }

    public function fetchTasks(): void
    {
        $db = $this->conn;
        $query = "SELECT * FROM tasks WHERE userId='" . $this->userEmail . "';";
        $stmt = $db->prepare($query);

        try {
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        } catch (PDOException $e) {
            echo json_encode(["msg" => "Error:" . $e->getMessage()]);
        }
        $stmt->closeCursor();
    }
    public function updateTask(int $id, array $updates)
    {
        $db = $this->conn;
        $query = "UPDATE tasks SET title, description VALUES('" . $updates['title'] . "','" . $updates['description'] . "') WHERE id=$id;";
        $stmt = $db->prepare($query);

        try {
            //prepare query
            $params = "";

            foreach ($updates as $key => $value) {
                $params = $params . " $key=:$key,";
            };
            $params = substr($params, 0, -1);
            $query = "UPDATE tasks SET $params WHERE userId ='" . $this->userEmail . "'AND id = $id;";
            $stmt = $this->conn->prepare($query);

            //bind credentials
            foreach ($updates as $key => $value) {
                $stmt->bindValue(":$key", "$value");
            };
            $stmt->execute();
            echo json_encode(["msg" => "success"]);
        } catch (PDOException $e) {
            echo json_encode(["msg" => "Error:" . $e->getMessage()]);
        }
        $stmt->closeCursor();
    }
    public function deleteTask(int $id)
    {
        $db = $this->conn;
        $query = "DELETE FROM tasks WHERE id='" . $id . "';";
        $stmt = $db->prepare($query);

        try {
            $stmt->execute();
            echo json_encode(["msg" => "success"]);
        } catch (PDOException $e) {
            echo json_encode(["msg" => "Error:" . $e->getMessage()]);
        }
        $stmt->closeCursor();
    }
}
