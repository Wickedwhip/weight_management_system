🏋️‍♂️ Weight Management System

A personalized fitness and reminder web app built with PHP, MySQL, HTML, CSS, and JavaScript.
It helps users manage their fitness goals, track routines, and receive automated email reminders to stay consistent with workouts, supplements, and healthy habits.

✨ Key Features

🧍‍♂️ User Registration & Login – Secure authentication and profile management

📅 Smart Reminder Scheduling – Set workout or diet reminders with custom messages

📧 Automated Email Notifications – PHP-based background job (cron/task scheduler) sends reminders via Gmail SMTP

📊 Progress Tracking – View past and upcoming reminders

🕒 Admin Panel – Manage users, reminders, and system activity

💾 MySQL Integration – All data stored in a relational database with clean structure

⚙️ Tech Stack

Frontend: HTML, CSS, JavaScript

Backend: PHP (XAMPP / Apache)

Database: MySQL

Email System: PHPMailer + Gmail SMTP

Automation: Windows Task Scheduler / Cron Job

🧩 Folder Structure
weight_management_system/
│
├── config/               # Database and email configuration
├── includes/             # Helper functions and mail setup
├── tasks/                # Automated scripts (send_reminders.php)
├── public/               # Frontend files (HTML, JS, CSS)
├── assets/               # Icons, images, and UI components
└── README.md

🚀 How It Works

Users create reminders through the dashboard.

The system stores them in the database with a scheduled time.

send_reminders.php checks for due reminders and sends emails automatically.

Once sent, the status updates in real time (sent = 1).

💡 Example Use Case

“Remind me to do push-ups at 10:00 AM every day.”
The system sends an email at the set time with your personalized message.

🧑‍💻 Developer Info

Designed and developed as a modern academic-level PHP project with practical real-world features.
Perfect for students, freelancers, or startups exploring health-tech web apps.