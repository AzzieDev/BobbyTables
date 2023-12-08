<!DOCTYPE html>
<html>

<head>
    <title>SQL Injection Demo</title>
</head>

<body>
    <form id="form" action="process.php" method="post">
        <label for="student_name">Enter Student Name:</label>
        <input type="text" id="student_name" name="student_name" value=""><br>
        <input type="submit" value="Submit">
    </form>
    <button onclick="populateForm(1)">Robert\'); DROP TABLE Students; -- </button>
    <button onclick="populateForm(2)">Populate/Reset Students table</button>
    <script>
        //passes data to the text field without submitting
        function populateForm(option) {
            let data = "";
            if (option === 1) {
                data = "'Robert'); DROP TABLE Students; -- '";
            } else if (option === 2) {
                data = "reset";
            }
            document.getElementById("student_name").value = data;
        } </script>
</body>

</html>