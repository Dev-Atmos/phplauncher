# 🧰 PHP Multi-Version Project Launcher

A lightweight browser-based toolkit to launch local PHP projects with specific PHP versions using built-in web servers—without relying on WAMP/XAMPP or administrative rights.

---

## 🚀 Why This Exists

This tool was created to help PHP developers run multiple projects with different PHP versions *without having to request system admin access repeatedly*. It enables independent and flexible development while respecting system boundaries and access policies.

---

## 🔧 Features

- Dynamic browsing of project directories (WAMP/XAMPP roots)
- Selectable PHP version for each project
- One-click project launch using PHP’s built-in server
- Runs via `.bat` script to bypass shell_exec() restrictions
- Web-based UI built with Bootstrap 5 for intuitive interaction
- GitHub-ready modular structure for collaboration and reuse

---

## 🗂️ Folder Structure

phpLauncher/ ├── public/ # Frontend UI and entry point │ ├── index.php # Main launcher interface │ └── assets/ # (Optional) custom styling ├── config/ │ └── settings.php # Root paths and available PHP versions ├── src/ │ └── Launcher.php # Core logic to scan and launch projects ├── .gitignore ├── README.md


---

## ⚙️ Requirements

- Windows OS
- WAMP or XAMPP installed (or any setup with multiple PHP versions)
- PHP 5.4+ for built-in server support
- Ability to run `.bat` scripts locally

---

## 🛠️ Setup Guide

1. Clone this repository:
   ```bash
   git clone https://github.com/dev-atmos/php-launcher.git
Configure your root directories and PHP versions in config/settings.php.

Serve public/index.php via WAMP or XAMPP's Apache server.

Create a .bat file to launch Apache and open the launcher:

bat
start "" "http://localhost/phpLauncher/public/index.php"
Open your launcher in the browser and select a project + PHP version.

🧪 Notes
By design, this tool does not modify server configs or require elevated permissions.

Launching uses shell_exec() via .bat file. Direct shell execution may not work under restricted WAMP environments.

Port numbers are randomly assigned within the 8000–8999 range to avoid clashes.

✅ Legal & Ethical Consideration
This tool is meant to enhance developer productivity in non-invasive ways. It does not bypass security boundaries, elevate permissions, or interfere with system processes. Developers should consult their system admin if uncertain about organizational policy before use.

📜 License
This project is licensed under the MIT License — feel free to use, modify, and share.

👤 Author
Developed by [Your Name] GitHub: @dev-atmos

Feel free to open issues, suggest improvements, or fork it into something even more dynamic!