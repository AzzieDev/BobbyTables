<!DOCTYPE html>
<html>

<head>
    <title>SQL Injection Demo</title>
</head>

<body>
    <h1>Demonstrate SQL Injection with Bobby Tables</h1>
    <p><button onclick="populateForm(1)">Robert'); DROP TABLE Students; -- </button>
        <br>
    <form id="form" action="#" method="post">
        <label for="student_name">Enter Student Name:</label>
        <br>
        <input type="text" id="student_name" name="student_name" style="width: 300px;"><br>
        <input type="checkbox" name="sanitize" id="sanitize" />
        <label for="sanitize">Sanitize Input</label><br>
        <br>
        <input type="submit" value="Submit">
        <br><br><button onclick="populateForm(2)">Reset Students table</button><br>
    </form>
    <script>
        //passes data to the text field without submitting
        function populateForm(option) {
            let data = "";
            if (option === 1) {
                data = "Robert'); DROP TABLE Students; -- ";
            } else if (option === 2) {
                data = "reset";
            }
            document.getElementById("student_name").value = data;
        }
    </script>

    <?php
    require_once('dbsecrets.php');

    if (isset($_POST['student_name'])) {
        //retrieve from the form for further processing without sanitization
        if (isset($_POST['sanitize'])) {
            $student_name = mysqli_real_escape_string($connection, $_POST['student_name']);
        } else {
            $student_name = $_POST['student_name'];
        }

        //allow us to regenerate the table if we wiped it :)
        if ($student_name == "reset") {
            $query = "
        DROP TABLE IF EXISTS Students;
        
        CREATE TABLE Students (
            first VARCHAR(50)
        );
        
        INSERT INTO Students VALUES
        ('Alice'),
        ('Bob'),
        ('Charlie'),
        ('David'),
        ('Eve');
    ";

            $result = mysqli_multi_query($connection, $query);

            while (mysqli_next_result($connection)) {
                if ($result = mysqli_store_result($connection)) {
                    mysqli_free_result($result);
                }
            }
        } else {

            // Unsafe query prone to SQL injection
            //$unsafe_query = "INSERT INTO `Students` VALUES ('Robert'); -- DROP TABLE Students;";
            //check if table exists
            $sql = "SHOW TABLES LIKE 'Students'";
            $result = mysqli_query($connection, $sql);

            if (mysqli_num_rows($result) == 1) {
                $unsafe_query = "INSERT INTO `Students` VALUES ('$student_name');";
                //$result = $connection->multi_query($unsafe_query);

                echo "<h2>" . $unsafe_query . "</h2><br>";
                $result = mysqli_multi_query($connection, $unsafe_query);
                // Discard the results without fetching or processing them
                while (mysqli_next_result($connection)) {
                    if ($result = mysqli_store_result($connection)) {
                        mysqli_free_result($result);
                    }
                }
            }
        }

        //check if table exists
        $sql = "SHOW TABLES LIKE 'Students'";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) == 1) {
            $query = "SELECT * FROM Students";
            $result = mysqli_query($connection, $query);

            echo "<br><h3>List of students:</h3><ul>";
            // Fetch data
            while ($row = mysqli_fetch_assoc($result)) {
                // Display student data (for demonstration)
                echo "<li>" . $row['first'] . "</li>";
            }
            echo "</ul>";
            // Close statement and connection
            mysqli_close($connection);
        } else {
            echo "<br><h2>Error: There is no Students Table found. Reset the table.</h2>";
        }
    }
    ?>
</body>

</html>
