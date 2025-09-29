# Library Management System

A simple web-based library management system built with PHP and MySQL.

## Features

**Admin Panel:**

- Manage books and students
- Process book issues and returns
- View reports and statistics
- Handle student requests

**Student Portal:**

- Browse available books
- Request book issues/returns
- View borrowing history
- Message system

## Installation

1. **Requirements:**

   - XAMPP (Apache + MySQL + PHP)
   - Web browser

2. **Setup:**

   ```bash
   # Clone or download to htdocs folder
   git clone https://github.com/nlenjibi/libraryManagement-System.git

   # Move to: C:\xampp\htdocs\libraryManagement\
   ```

3. **Database:**

   - Start XAMPP (Apache + MySQL)
   - Open http://localhost/phpmyadmin
   - Create database: `LMS`
   - Import all SQL files from `/database/` folder
   - Run: `ALTER TABLE user ADD COLUMN ProfilePic VARCHAR(255) NULL;`

4. **Access:**
   - Visit: http://localhost/libraryManagement
   - Admin login: `ADMIN` / `admin`
   - Student login: `b160001cs` / `b160001cs`

## Project Structure

```
libraryManagement/
├── index.php          # Main login page
├── dbconn.php         # Database connection
├── admin/             # Admin interface
├── student/           # Student interface
├── database/          # SQL files
├── css/               # Stylesheets
└── images/            # System images
```

## Technologies

- PHP with PDO
- MySQL database
- Bootstrap CSS
- HTML/CSS/JavaScript

## License

MIT License - see LICENSE file for details.

## Author

[nlenjibi](https://github.com/nlenjibi)
