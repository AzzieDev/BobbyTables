# BobbyTables
Demonstrates SQL injection from XKCD 327 in PHP 8.2 and MariaDB 10.3 (MySQL)

![XKCD 327](https://imgs.xkcd.com/comics/exploits_of_a_mom.png)

This repository contains a simple PHP script that shows how SQL injection can be exploited by malicious users. The script takes a user input from a form and uses it to query a database of student records. However, the script does not sanitize the input or use prepared statements, which makes it vulnerable to SQL injection attacks.

## How to run
- Clone this repository or download the zip file
- Install PHP 8.2 or higher on your machine
- Install MySQL or any compatible database server
- Create a database and edit the `dbsecrets.php` file and enter your database credentials
- Run the `index.php` file on your web server or using a built-in PHP server
- Enter "reset" or click the Reset button in the form to generate a table and populate it with sample data
- Enter a student name in the form and see the results change
- Try entering some malicious inputs or click the  `Robert'); DROP TABLE students; --` button and see what happens
- Test with the Sanitize Input option enabled and disabled
- Applicable SQL statements are displayed on screen

## How to prevent SQL injection
- Use prepared statements and parameterized queries instead of concatenating user inputs into SQL queries
- Use a web application firewall (WAF) or other security tools to filter out malicious inputs
- Validate and sanitize user inputs before using them in any database operations
- Use the least privilege principle and limit the permissions of the database user
- Encrypt sensitive data and use secure connections

## References
- [XKCD 327: Exploits of a Mom](https://xkcd.com/327/)
- [PHP: SQL Injection - Manual](https://www.php.net/manual/en/security.database.sql-injection.php)
- [OWASP: SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html) 
