<?php
class AccountModel {
private $conn;
private $table_name = "account";

public function __construct($db) {
    $this->conn = $db;
}

public function getAccountByUsername($username) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

public function save($username, $fullName, $password, $role = 'user') {
    // Validate role is one of the allowed roles
    if (!in_array($role, ['admin', 'employee', 'user'])) {
        $role = 'user'; // Default to user if invalid role
    }
    
    if ($this->getAccountByUsername($username)) {
        return false;
    }
    
    $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role";
    $stmt = $this->conn->prepare($query);
    
    $username = htmlspecialchars(strip_tags($username));
    $fullName = htmlspecialchars(strip_tags($fullName));
    $password = password_hash($password, PASSWORD_BCRYPT);
    $role = htmlspecialchars(strip_tags($role));
    
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":fullname", $fullName);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":role", $role);
    
    return $stmt->execute();
}

// New method for saving with all details
public function saveWithDetails($username, $fullName, $password, $role = 'user', $email = '', $sdt = '', $dia_chi = '', $avatar = '') {
    // Validate role is one of the allowed roles
    if (!in_array($role, ['admin', 'employee', 'user'])) {
        $role = 'user'; // Default to user if invalid role
    }
    
    if ($this->getAccountByUsername($username)) {
        return false;
    }
    
    $query = "INSERT INTO " . $this->table_name . " 
             SET username=:username, fullname=:fullname, password=:password, role=:role, 
                 email=:email, sdt=:sdt, dia_chi=:dia_chi";
    
    // Add avatar field if provided
    if (!empty($avatar)) {
        $query .= ", avatar=:avatar";
    }
    
    $stmt = $this->conn->prepare($query);
    
    $username = htmlspecialchars(strip_tags($username));
    $fullName = htmlspecialchars(strip_tags($fullName));
    $password = password_hash($password, PASSWORD_BCRYPT);
    $role = htmlspecialchars(strip_tags($role));
    $email = htmlspecialchars(strip_tags($email));
    $sdt = htmlspecialchars(strip_tags($sdt));
    $dia_chi = htmlspecialchars(strip_tags($dia_chi));
    
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":fullname", $fullName);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":role", $role);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":sdt", $sdt);
    $stmt->bindParam(":dia_chi", $dia_chi);
    
    if (!empty($avatar)) {
        $stmt->bindParam(":avatar", $avatar);
    }
    
    return $stmt->execute();
}

// Get all users from database
public function getAllUsers() {
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

// Get a specific user by ID
public function getUserById($id) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

// Update an existing user
public function updateUser($id, $fullName, $role, $password = null) {
    // Validate role
    if (!in_array($role, ['admin', 'employee', 'user'])) {
        $role = 'user';
    }
    
    // If password is being updated
    if ($password) {
        $query = "UPDATE " . $this->table_name . " SET fullname=:fullname, role=:role, password=:password WHERE id=:id";
    } else {
        $query = "UPDATE " . $this->table_name . " SET fullname=:fullname, role=:role WHERE id=:id";
    }
    
    $stmt = $this->conn->prepare($query);
    
    $fullName = htmlspecialchars(strip_tags($fullName));
    $role = htmlspecialchars(strip_tags($role));
    $id = htmlspecialchars(strip_tags($id));
    
    $stmt->bindParam(":fullname", $fullName);
    $stmt->bindParam(":role", $role);
    $stmt->bindParam(":id", $id);
    
    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $hashed_password);
    }
    
    return $stmt->execute();
}

// Update user with all details
public function updateUserWithDetails($id, $fullName, $role, $email = '', $sdt = '', $dia_chi = '', $avatar = '', $password = null) {
    // Validate role
    if (!in_array($role, ['admin', 'employee', 'user'])) {
        $role = 'user';
    }
    
    // Start building the query
    $query = "UPDATE " . $this->table_name . " 
             SET fullname=:fullname, role=:role, email=:email, sdt=:sdt, dia_chi=:dia_chi";
    
    // Add avatar field if provided
    if (!empty($avatar)) {
        $query .= ", avatar=:avatar";
    }
    
    // If password is being updated
    if ($password) {
        $query .= ", password=:password";
    }
    
    $query .= " WHERE id=:id";
    
    $stmt = $this->conn->prepare($query);
    
    $fullName = htmlspecialchars(strip_tags($fullName));
    $role = htmlspecialchars(strip_tags($role));
    $email = htmlspecialchars(strip_tags($email));
    $sdt = htmlspecialchars(strip_tags($sdt));
    $dia_chi = htmlspecialchars(strip_tags($dia_chi));
    $id = htmlspecialchars(strip_tags($id));
    
    $stmt->bindParam(":fullname", $fullName);
    $stmt->bindParam(":role", $role);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":sdt", $sdt);
    $stmt->bindParam(":dia_chi", $dia_chi);
    $stmt->bindParam(":id", $id);
    
    if (!empty($avatar)) {
        $stmt->bindParam(":avatar", $avatar);
    }
    
    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $hashed_password);
    }
    
    return $stmt->execute();
}

// Delete a user
public function deleteUser($id) {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $id = htmlspecialchars(strip_tags($id));
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

// Check if username exists (for validation)
public function usernameExists($username, $exclude_id = null) {
    if ($exclude_id) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE username = :username AND id != :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":id", $exclude_id);
    } else {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
    }
    
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}
}
?>