<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include 'db.php';

// Flag for alert message
$alert_type = '';
$alert_message = '';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $conn->query("INSERT INTO students (first_name, last_name, email, phone) VALUES ('$first_name', '$last_name', '$email', '$phone')");
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $conn->query("UPDATE students SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone' WHERE id=$id");
        // Set success message for edit
        $alert_type = 'green';
        $alert_message = 'Edited student successfully';
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $conn->query("DELETE FROM students WHERE id=$id");
        // Set success message for delete
        $alert_type = 'red';
        $alert_message = 'Deleted student data';
    }
}

$students = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container-dashboard">
        <div class="form-section">
            <!-- Header Section -->
            <div class="nav-bar">
                <!-- Retained the original header -->
                <h1 class="admin-dashboard">Dashboard</h1>
                <button class="burger-menu"><i class="fa fa-bars"></i></button>
                <!-- Dropdown Menu for Logout -->
                <div class="dropdown">
                    <div class="dropdown-content">
                        <a href="logout.php" class="logout-link">Logout</a>
                    </div>
                </div>
            </div>

            <!-- Add Student and Search Section -->
            <div class="action-section">
                <!-- + Add Student Button from index.html -->
                <button class="add-student-btn" onclick="showPopup()"><i class="fa fa-plus"></i> ㅤAdd Student</button>

                <!-- Search Bar from index.html -->
                <div class="search-container">
                    <i class="fa fa-search"></i>
                    <input 
                        type="text" 
                        class="search-bar" 
                        id="searchBar" 
                        placeholder="Search by ID or Last Name" 
                        style="color: #19949B; font-weight: 600;"
                        onkeyup="filterStudents()">
                </div>
            </div>

            <!-- Alert Messages for Success (Green for Edit, Red for Delete) -->
            <?php if ($alert_type && $alert_message): ?>
                <div id="alert" class="alert alert-<?php echo $alert_type; ?>">
                    <?php echo $alert_message; ?>
                </div>
            <?php endif; ?>

            <!-- Student Table -->
            <div class="table-container">
                <h2>List of Students</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentTable" class="student-table-body">
                        <?php while ($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['first_name'] ?></td>
                            <td><?= $row['last_name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['phone'] ?></td>
                            <td class="actions-td">
                                <!-- Edit and Delete forms: original from admin.php -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="text" name="first_name" value="<?= $row['first_name'] ?>">
                                    <input type="text" name="last_name" value="<?= $row['last_name'] ?>">
                                    <input type="email" name="email" value="<?= $row['email'] ?>">
                                    <input type="text" name="phone" value="<?= $row['phone'] ?>">
                                    <button class="edit-btn" onclick="showAlert('green', 'Edited student successfully!')" type="submit" name="edit"><i class='fa fa-pencil'></i> Edit</button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button class="delete-btn" onclick="showAlert('red', 'Deleted student data!')" type="submit" name="delete"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Student Popup from index.html -->
        <div id="popup" class="popup" style="display: none;">
            <div class="popup-content">
                <h2>Add Student</h2>
                <form method="POST" class="add-student-form">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>

                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone">

                    <button type="submit" name="add">Add</button>
                    <button type="button" onclick="hidePopup()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show/Hide Add Student Popup from index.html
        function showPopup() {
            document.getElementById('popup').style.display = 'flex';
        }

        function hidePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        // Show Alert Message from index.html
        function showAlert(type, message) {
            const alertBox = document.getElementById('alert');
            alertBox.style.display = 'block';
            alertBox.className = type === 'green' ? 'alert alert-green' : 'alert alert-red';
            alertBox.innerText = message;

            // Set timeout for 10 seconds (10000 milliseconds)
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 1000); // 10 seconds
        }

        function addStudent(event) {
            event.preventDefault();
            showAlert('green', 'Added student successfully!');
            hidePopup();
        }


        // Filter Students in Table from index.html
        function filterStudents() {
            const searchValue = document.getElementById('searchBar').value.toLowerCase();
            const tableRows = document.querySelectorAll('#studentTable tr');
            tableRows.forEach(row => {
                const id = row.cells[0].innerText.toLowerCase();
                const last_name = row.cells[2].innerText.toLowerCase();
                if (id.includes(searchValue) || last_name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }


        // Toggle Dropdown Menu
        // Get the burger menu button and dropdown
        const burgerMenu = document.querySelector('.burger-menu');
        const dropdown = document.querySelector('.dropdown');

        // Toggle the dropdown menu when the burger menu is clicked
        burgerMenu.addEventListener('click', function() {
            dropdown.classList.toggle('show');
        });



    </script>


    <!-- Footer Section -->
    <footer class="footer">
        <p>©mrspecific2024</p>
    </footer>

</body>

</html>
