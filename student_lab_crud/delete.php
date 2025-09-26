<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'delete') {
    try {
        $id = (int)$_POST['id'];
        
        if ($id <= 0) {
            throw new Exception("Invalid student ID.");
        }
        
        $checkStmt = $pdo->prepare("SELECT first_name, last_name FROM students WHERE id = ?");
        $checkStmt->execute([$id]);
        $student = $checkStmt->fetch();
        
        if (!$student) {
            throw new Exception("Student not found.");
        }
        
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            $studentName = $student['first_name'] . ' ' . $student['last_name'];
            header("Location: index.php?message=" . urlencode("Student '{$studentName}' deleted successfully!") . "&type=success");
        } else {
            throw new Exception("Failed to delete student.");
        }
        exit();
        
    } catch (Exception $e) {
        header("Location: index.php?message=" . urlencode($e->getMessage()) . "&type=danger");
        exit();
    }
}
?>
