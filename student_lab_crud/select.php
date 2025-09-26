<?php
require_once 'db_connect.php';

// Fetch all students
$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Students - Student Management System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 20px auto;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .student-count-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .btn-custom {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <!-- Header -->
            <div class="header-gradient text-white text-center py-5">
                <div class="container">
                    <h1 class="display-4 fw-light mb-3">
                        <i class="bi bi-people-fill me-3"></i>All Students
                    </h1>
                </div>
            </div>
            
            <div class="container py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="index.php" class="btn btn-primary btn-custom">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card student-count-card">
                            <div class="card-body text-center">
                                <h3 class="card-title mb-2">
                                    <i class="bi bi-people-fill me-2"></i>Total Students: <?php echo count($students); ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (count($students) > 0): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card table-container">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-table me-2"></i>Student Records
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="studentsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Course</th>
                                                    <th>Year Level</th>
                                                    <th>Created</th>
                                                    <th>Updated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($students as $student): ?>
                                                    <tr>
                                                        <td><?php echo $student['id']; ?></td>
                                                        <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['phone'] ?: 'N/A'); ?></td>
                                                        <td><?php echo htmlspecialchars($student['course'] ?: 'N/A'); ?></td>
                                                        <td><?php echo htmlspecialchars($student['year_level'] ?: 'N/A'); ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($student['created_at'])); ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($student['updated_at'])); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="bi bi-book display-1 text-muted mb-3"></i>
                                    <h3 class="text-muted">No Students Found</h3>
                                    <p class="text-muted">No students have been registered yet.</p>
                                    <a href="index.php" class="btn btn-primary btn-custom">
                                        <i class="bi bi-person-plus-fill me-2"></i>Add First Student
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 10
            });
        });
    </script>
</body>
</html>