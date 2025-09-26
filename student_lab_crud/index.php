<?php
require_once 'db_connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                include 'insert.php';
                break;
            case 'update':
                include 'update.php';
                break;
            case 'delete':
                include 'delete.php';
                break;
        }
    }
}

// Fetch all students
$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    
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
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        
        .sortable {
            cursor: pointer;
            user-select: none;
        }
        
        .sortable:hover {
            background-color: rgba(0,0,0,0.05);
        }
        
        .sort-indicator {
            margin-left: 5px;
        }
        
        .is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            display: block;
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
                        <i class="bi bi-mortarboard-fill me-3"></i>Student Management System
                    </h1>
                    
                </div>
            </div>
            
            <div class="container py-4">
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-<?php echo $_GET['type'] ?? 'success'; ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-<?php echo $_GET['type'] === 'danger' ? 'exclamation-triangle' : 'check-circle'; ?>-fill me-2"></i>
                        <?php echo htmlspecialchars($_GET['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
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
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                <i class="bi bi-person-plus-fill me-2"></i>Add New Student
                            </button>
                            <a href="select.php" class="btn btn-success btn-custom">
                                <i class="bi bi-list-ul me-2"></i>View All Students
                            </a>
                            <button class="btn btn-info btn-custom" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                            </button>
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
                                                    <th class="sortable" onclick="sortTable(0)">ID <i class="bi bi-arrow-down-up sort-indicator"></i></th>
                                                    <th class="sortable" onclick="sortTable(1)">Name <i class="bi bi-arrow-down-up sort-indicator"></i></th>
                                                    <th class="sortable" onclick="sortTable(2)">Email <i class="bi bi-arrow-down-up sort-indicator"></i></th>
                                                    <th>Phone</th>
                                                    <th>Course</th>
                                                    <th>Year Level</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($students as $student): ?>
                                                    <tr>
                                                        <td><?php echo $student['id']; ?></td>
                                                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['phone'] ?: 'N/A'); ?></td>
                                                        <td><?php echo htmlspecialchars($student['course'] ?: 'N/A'); ?></td>
                                                        <td><?php echo htmlspecialchars($student['year_level'] ?: 'N/A'); ?></td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <button class="btn btn-warning btn-sm btn-custom" onclick="editStudent(<?php echo htmlspecialchars(json_encode($student)); ?>)">
                                                                    <i class="bi bi-pencil-fill"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-sm btn-custom" onclick="confirmDelete(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>')">
                                                                    <i class="bi bi-trash-fill"></i>
                                                                </button>
                                                            </div>
                                                        </td>
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
                                    <p class="text-muted">Start by adding your first student to the system!</p>
                                    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                        <i class="bi bi-person-plus-fill me-2"></i>Add First Student
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addStudentModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Add New Student
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="addStudentForm" novalidate>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                <div class="invalid-feedback">Please provide a valid first name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                                <div class="invalid-feedback">Please provide a valid last name.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please provide a valid email address.</div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" class="form-control" id="course" name="course">
                        </div>
                        <div class="mb-3">
                            <label for="year_level" class="form-label">Year Level</label>
                            <select class="form-select" id="year_level" name="year_level">
                                <option value="">Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editStudentModalLabel">
                        <i class="bi bi-pencil-fill me-2"></i>Edit Student
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="editStudentForm" novalidate>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                                <div class="invalid-feedback">Please provide a valid first name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                                <div class="invalid-feedback">Please provide a valid last name.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                            <div class="invalid-feedback">Please provide a valid email address.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="edit_course" class="form-label">Course</label>
                            <input type="text" class="form-control" id="edit_course" name="course">
                        </div>
                        <div class="mb-3">
                            <label for="edit_year_level" class="form-label">Year Level</label>
                            <select class="form-select" id="edit_year_level" name="year_level">
                                <option value="">Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="5th Year">5th Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete student <strong id="deleteStudentName"></strong>?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Student</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        function editStudent(student) {
            document.getElementById('edit_id').value = student.id;
            document.getElementById('edit_first_name').value = student.first_name;
            document.getElementById('edit_last_name').value = student.last_name;
            document.getElementById('edit_email').value = student.email;
            document.getElementById('edit_phone').value = student.phone || '';
            document.getElementById('edit_course').value = student.course || '';
            document.getElementById('edit_year_level').value = student.year_level || '';
            
            var editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
            editModal.show();
        }
        
        function confirmDelete(id, name) {
            document.getElementById('deleteStudentName').textContent = name;
            document.getElementById('confirmDeleteBtn').onclick = function() {
                deleteStudent(id);
            };
            
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteModal.show();
        }
        
        function deleteStudent(id) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="${id}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        function sortTable(columnIndex) {
            const table = document.getElementById('studentsTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            const isAscending = table.getAttribute('data-sort-direction') !== 'asc';
            table.setAttribute('data-sort-direction', isAscending ? 'asc' : 'desc');
            
            rows.sort((a, b) => {
                const aValue = a.cells[columnIndex].textContent.trim();
                const bValue = b.cells[columnIndex].textContent.trim();
                
                if (columnIndex === 0) {
                    return isAscending ? aValue - bValue : bValue - aValue;
                }
                
                return isAscending ? 
                    aValue.localeCompare(bValue) : 
                    bValue.localeCompare(aValue);
            });
            
            rows.forEach(row => tbody.appendChild(row));
            
            const headers = table.querySelectorAll('th');
            headers.forEach((header, index) => {
                const indicator = header.querySelector('.sort-indicator');
                if (index === columnIndex) {
                    indicator.className = `bi bi-arrow-${isAscending ? 'up' : 'down'} sort-indicator`;
                } else {
                    indicator.className = 'bi bi-arrow-down-up sort-indicator';
                }
            });
        }
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "pageLength": 10,
                "language": {
                    "search": "Search students:",
                    "lengthMenu": "Show _MENU_ students per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ students",
                    "infoEmpty": "No students found",
                    "infoFiltered": "(filtered from _MAX_ total students)"
                }
            });
        });
        
        document.getElementById('addStudentForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
        
        document.getElementById('editStudentForm').addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    </script>
</body>
</html>
