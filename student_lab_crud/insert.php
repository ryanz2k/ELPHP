<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
    try {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $course = trim($_POST['course']);
        $year_level = trim($_POST['year_level']);
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            throw new Exception("First name, last name, and email are required fields.");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address.");
        }
        
        $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $checkStmt->execute([$email]);
        if ($checkStmt->fetch()) {
            throw new Exception("A student with this email already exists.");
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO students (first_name, last_name, email, phone, course, year_level) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $first_name,
            $last_name,
            $email,
            $phone ?: null,
            $course ?: null,
            $year_level ?: null
        ]);
        
        header("Location: index.php?message=" . urlencode("Student added successfully!") . "&type=success");
        exit();
        
    } catch (Exception $e) {
        header("Location: index.php?message=" . urlencode($e->getMessage()) . "&type=danger");
        exit();
    }
}
?>
