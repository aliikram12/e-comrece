<?php
// User class for user operations

class User {
    private $conn;
    private $table = 'users';
    
    public $user_id;
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $password;
    public $role;
    public $status;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Register new user
    public function register($username, $email, $password, $first_name = '', $last_name = '') {
        // Check if user already exists
        if ($this->userExists($email)) {
            return array('success' => false, 'message' => 'Email already registered');
        }
        
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO $this->table (username, email, password, first_name, last_name, role, status) 
                  VALUES (?, ?, ?, ?, ?, 'customer', 'active')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $username, $email, $password_hash, $first_name, $last_name);
        
        if ($stmt->execute()) {
            return array('success' => true, 'user_id' => $stmt->insert_id, 'message' => 'User registered successfully');
        }
        
        return array('success' => false, 'message' => 'Registration failed');
    }
    
    // Register new user with optional role and status
    public function registerWithRole($username, $email, $password, $first_name = '', $last_name = '', $role = 'customer', $status = 'active') {
        if ($this->userExists($email)) {
            return array('success' => false, 'message' => 'Email already registered');
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO $this->table (username, email, password, first_name, last_name, role, status) \
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssss", $username, $email, $password_hash, $first_name, $last_name, $role, $status);

        if ($stmt->execute()) {
            return array('success' => true, 'user_id' => $stmt->insert_id, 'message' => 'User registered successfully');
        }

        return array('success' => false, 'message' => 'Registration failed');
    }
    
    // Login user
    public function login($email, $password) {
        $query = "SELECT user_id, username, email, password, role, status FROM $this->table WHERE email = ? AND status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                return array('success' => true, 'message' => 'Login successful');
            }
        }
        
        return array('success' => false, 'message' => 'Invalid email or password');
    }
    
    // Logout user
    public function logout() {
        session_unset();
        session_destroy();
        return array('success' => true, 'message' => 'Logout successful');
    }
    
    // Check if user exists
    private function userExists($email) {
        $query = "SELECT user_id FROM $this->table WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        return $stmt->get_result()->num_rows > 0;
    }
    
    // Get all users for admin
    public function getAllUsers($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT user_id, username, email, first_name, last_name, phone, role, status, profile_image, created_at FROM $this->table ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get total user count
    public function getTotalUsers() {
        $query = "SELECT COUNT(*) AS total FROM $this->table";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Delete a user
    public function deleteUser($user_id) {
        $query = "DELETE FROM $this->table WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
    
    // Update user details
    public function updateUser($user_id, $username, $email, $first_name, $last_name, $phone, $role, $status, $profile_image = null) {
        if ($profile_image !== null) {
            $query = "UPDATE $this->table SET username = ?, email = ?, first_name = ?, last_name = ?, phone = ?, role = ?, status = ?, profile_image = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssssssssi", $username, $email, $first_name, $last_name, $phone, $role, $status, $profile_image, $user_id);
        } else {
            $query = "UPDATE $this->table SET username = ?, email = ?, first_name = ?, last_name = ?, phone = ?, role = ?, status = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssssssi", $username, $email, $first_name, $last_name, $phone, $role, $status, $user_id);
        }

        return $stmt->execute();
    }
    
    // Get admin count
    public function getAdminCount() {
        $query = "SELECT COUNT(*) AS total FROM $this->table WHERE role = 'admin'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Get user by ID
    public function getUserById($user_id) {
        $query = "SELECT user_id, username, email, first_name, last_name, phone, role, profile_image FROM $this->table WHERE user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Update user profile
    public function updateProfile($user_id, $first_name, $last_name, $phone, $profile_image = null) {
        if ($profile_image !== null) {
            $query = "UPDATE $this->table SET first_name = ?, last_name = ?, phone = ?, profile_image = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $first_name, $last_name, $phone, $profile_image, $user_id);
        } else {
            $query = "UPDATE $this->table SET first_name = ?, last_name = ?, phone = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $first_name, $last_name, $phone, $user_id);
        }

        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'Profile updated successfully');
        }

        return array('success' => false, 'message' => 'Profile update failed');
    }
    
    // Change password
    public function changePassword($user_id, $old_password, $new_password) {
        $user = $this->getUserById($user_id);
        
        if (!$user) {
            return array('success' => false, 'message' => 'User not found');
        }
        
        $query = "SELECT password FROM $this->table WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if (!password_verify($old_password, $result['password'])) {
            return array('success' => false, 'message' => 'Old password is incorrect');
        }
        
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        
        $update_query = "UPDATE $this->table SET password = ? WHERE user_id = ?";
        $update_stmt = $this->conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_password_hash, $user_id);
        
        if ($update_stmt->execute()) {
            return array('success' => true, 'message' => 'Password changed successfully');
        }
        
        return array('success' => false, 'message' => 'Password change failed');
    }
}
?>
