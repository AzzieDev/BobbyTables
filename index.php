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

	// Discard the results without fetching or processing them
	function discardResult($connection) {
		while (mysqli_next_result($connection)) {
			if ($result = mysqli_store_result($connection)) {
				mysqli_free_result($result);
			}
		}
	}

	function resetData($connection) {
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
		discardResult($connection);
	}

	if (isset($_POST['student_name'])) {
		//retrieve from the form for further processing
		$student_name = $_POST['student_name'];
		//sanitize using built-in escaping function if option is selected
		if (isset($_POST['sanitize'])) {
			$student_name = mysqli_real_escape_string($connection, $student_name);
		}

		//allow us to regenerate the table if we wiped it :)
		if ($student_name == "reset") {
			resetData($connection);
		} else {

			//check if table exists
			$sql = "SHOW TABLES LIKE 'Students'";
			$result = mysqli_query($connection, $sql);

			if (mysqli_num_rows($result) == 1) {
				// query prone to SQL injection
				$query = "INSERT INTO `Students` VALUES ('$student_name');";

				echo "<h2>" . $query . "</h2><br>";
				$result = mysqli_multi_query($connection, $query);

				discardResult($connection);
			}
		}

		//check if table exists
		$sql = "SHOW TABLES LIKE 'Students'";
		$result = mysqli_query($connection, $sql);

		if (mysqli_num_rows($result) == 1) {
			$query = "SELECT * FROM Students ORDER BY first ASC";
			$result = mysqli_query($connection, $query);

			echo "<br><h3>List of students:</h3><ul>";
			// Fetch data
			$count = 0;
			while ($row = mysqli_fetch_assoc($result)) {
				// Display student data (for demonstration)
				echo "<li>" . $row['first'] . "</li>";
				$count++;
			}
			echo "</ul>";
			//limit to only allow 20 student records
			if ($count > 20) {
				resetData($connection);
			}
		} else {
			echo "<br><h2>Error: No Students table found!<br>Reset the table to perform further tasks.</h2>";
		}
		// Close statement and connection
		mysqli_close($connection);
	}
	?>
</body>

</html>
