# Event Management System

## Project Overview
This is a fully functional **Event Management System** that allows users to:
- Sign up and log in.
- Add, update, and delete events.
- Receive email notifications **1 hour before an event**.
- Store event details securely in a MySQL database.
- Use a clean and professional UI built with **HTML, CSS, and JavaScript**.
- Handle authentication and database operations using **PHP**.

## Features
1. **User Authentication** (Login & Signup)
2. **Dashboard** displaying user-specific events.
3. **Event Management:**
   - Add new events.
   - Update existing events.
   - Delete events (with confirmation).
4. **Automatic Email Notifications** before events.
5. **Localhost SMTP Setup** for sending emails.

---

## Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL (phpMyAdmin in XAMPP)
- **Email Notification:** PHP Mail Function (Configured with Gmail SMTP)

---

## Installation and Setup Guide

### 1. Install XAMPP
Download and install **XAMPP** from [Apache Friends](https://www.apachefriends.org/). This includes Apache, PHP, and MySQL.

### 2. Clone or Download the Repository
```sh
 git clone https://github.com/Gayathri-Nandakumar16/event-management-system.git
```
Alternatively, you can download the ZIP file and extract it into the `htdocs` folder of XAMPP.

### 3. Configure Database
1. Open **XAMPP Control Panel** and start `Apache` and `MySQL`.
2. Open `http://localhost/phpmyadmin/` in your browser.
3. Create a new database called `event_management`.
4. Import the `db/schema.sql` file.

### 4. Configure PHP Mail for Email Notifications
1. Open `php.ini` (in `C:\xampp\php`):
   ```ini
   [mail function]
   SMTP=smtp.gmail.com
   smtp_port=587
   sendmail_from = your-email@gmail.com
   sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
   ```
2. Open `sendmail.ini` (in `C:\xampp\sendmail`):
   ```ini
   smtp_server=smtp.gmail.com
   smtp_port=587
   smtp_ssl=tls
   auth_username=your-email@gmail.com
   auth_password=your-gmail-app-password
   ```
3. Restart Apache in XAMPP.

### 5. Run the Project
1. Open your browser and go to `http://localhost/event-management-system/`.
2. Sign up, log in, and manage events.
3. Test email notifications by adding an event 1 hour from the current time.

### 6. Automate Notifications (Optional)
To check for upcoming events every minute, set up a cron job or use the command:
```sh
while true; do php send_notifications.php; sleep 60; done
```

---

## Folder Structure
```
/event-management-system
├── css/                 # Stylesheets
├── db/                  # Database schema & connection
│   ├── schema.sql       # SQL for database setup
│   ├── connection.php   # Database connection file
├── js/                  # JavaScript files
├── php/                 # Backend PHP scripts
│   ├── authenticate.php # User login
│   ├── signup_process.php # User signup
│   ├── add_event.php    # Add event logic
│   ├── update_event.php # Update event logic
│   ├── delete_event.php # Delete event logic
│   ├── send_notifications.php # Send email reminders
├── index.html           # Login page
├── signup.html          # Signup page
├── dashboard.html       # User dashboard
├── add_event.html       # Add event page
├── update_event.html    # Update event page
├── delete_event.html    # Delete event page
├── README.md            # Project documentation
```

---

## Troubleshooting
### Email Not Sending?
- Ensure you configured Gmail SMTP correctly.
- If using **Gmail**, enable "Less Secure Apps" or generate an **App Password**.
- Restart Apache after changing `php.ini`.
- Check `C:\xampp\apache\logs\error.log` for errors.

---

## Future Enhancements
- Implement **SMS Notifications** (using Twilio or a free API).
- Deploy project on a **live server**.
- Add a **calendar view** for events.

---

## Contact
For questions or issues, contact **gayathri16072004@gmail.com** or raise an issue on the repository.

Happy coding! 🚀

