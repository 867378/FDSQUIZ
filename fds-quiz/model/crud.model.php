<?php
interface CrudInterface {
    public function getAll();
    public function getOne($data);
    public function insert($data);
    public function update($data);
    public function delete($data);
}

class Crud_model {

    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAlldata() {
        $sql = "SELECT * FROM quizfds";
        return $this->executeQuery($sql);
    }

    public function getOnedata($data) {
        $sql = "SELECT * FROM quizfds WHERE ID = ?";
        return $this->executeQuery($sql, [$data->ID], "The user is not on the database");
    }

    public function insert($data) {
        if (empty($data->firstname) || empty($data->lastname)) {
            return "Error: firstname and lastname cannot be empty.";
        }
        $sql = "INSERT INTO quizfds(firstname, lastname, is_admin) VALUES(?, ?, Default)";
        return $this->executeQuery($sql, [$data->firstname, $data->lastname], 'Data Not Inserted', 'Data Inserted');
    }

    public function update($data) {
        $sql = "UPDATE quizfds SET is_admin = CASE WHEN is_admin = 0 THEN 1 WHEN is_admin = 1 THEN 0 END WHERE ID = ?";
        return $this->executeQuery($sql, [$data->ID], "Data Not Updated", "Data Updated");
    }

    public function delete($data) {
        $sql = "DELETE FROM quizfds WHERE ID = ?";
        return $this->executeQuery($sql, [$data->ID], "Delete Failed.", "Delete Success.");
    }
    private function executeQuery($sql, $params = [], $failureMessage = 'Error', $successMessage = null) {
        try {
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute($params)) {
                return $successMessage ?? ($stmt->rowCount() > 0 ? $stmt->fetchAll() : $failureMessage);
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
