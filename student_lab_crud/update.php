<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update') {
    try {
        $id = (int)$_POST['id'];
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
        
        $checkStmt = $pdo->prepare("SELECT id FROM students WHERE id = ?");
        $checkStmt->execute([$id]);
        if (!$checkStmt->fetch()) {
            throw new Exception("Student not found.");
        }
        
        $emailCheckStmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
        $emailCheckStmt->execute([$email, $id]);
        if ($emailCheckStmt->fetch()) {
            throw new Exception("A student with this email already exists.");
        }
        
        $stmt = $pdo->prepare("
            UPDATE students 
            SET first_name = ?, last_name = ?, email = ?, phone = ?, course = ?, year_level = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        $stmt->execute([
            $first_name,
            $last_name,
            $email,
            $phone ?: null,
            $course ?: null,
            $year_level ?: null,
            $id
        ]);
        
        header("Location: index.php?message=" . urlencode("Student updated successfully!") . "&type=success");
        exit();
        
    } catch (Exception $e) {
        header("Location: index.php?message=" . urlencode($e->getMessage()) . "&type=danger");
        exit();
    }
}
?>
