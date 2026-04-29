# Company Management System

A production-ready PHP-based Company Management System with MongoDB, email notifications, and cloud storage.

## Requirements

- **PHP** 8.0 or higher
- **Composer** (PHP package manager)
- **MongoDB Atlas** account (cloud database)
- **Web Server** (Apache/Nginx with PHP support)

## Quick Start

### 1. Install PHP Dependencies

```bash
composer install
```

### 2. Configure MongoDB

Edit `config/database.php` and replace the MongoDB connection string:

```php
private string $uri = 'mongodb+srv://<username>:<password>@<cluster>.mongodb.net/?retryWrites=true&w=majority';
```

- Create a free [MongoDB Atlas](https://www.mongodb.com/cloud/atlas) account
- Create a cluster and get your connection string
- Replace `<username>`, `<password>`, and `<cluster>` with your credentials

### 3. Configure Application Settings

Edit `config/app.php` to customize:

- `BASE_URL` - Your application URL
- `APP_ENV` - Set to `production` when deploying
- `OFFICE_START_TIME` / `OFFICE_END_TIME` - Working hours for attendance

### 4. (Optional) Configure Email

Edit `config/mail.php` to set up SMTP for sending emails.

### 5. (Optional) Configure Cloud Storage

Edit `config/cloudinary.php` to enable image uploads to Cloudinary.

### 6. Create Admin Account

1. Start your local web server
2. Open browser and navigate to:
   ```
   http://localhost/company-management-system/setup.php
   ```
3. Fill in the admin details and submit
4. **IMPORTANT:** Delete `setup.php` after creating the admin

### 7. Access the Application

```
http://localhost/company-management-system/
```

Login with your admin credentials.

---

## Running with Local Web Server

### Using PHP Built-in Server (Quickest)

```bash
cd "c:\Users\USER\Desktop\company management System"
php -S localhost:8000
```

Then open: `http://localhost:8000`

### Using XAMPP/WAMP

1. Move project to `htdocs` folder
2. Start Apache from XAMPP Control Panel
3. Access at: `http://localhost/company-management-system`

### Using Laravel Valet (macOS)

```bash
cd /path/to/project
valet link
```

---

## Project Structure

```
├── config/          # Configuration files
│   ├── app.php      # App settings
│   ├── database.php # MongoDB connection
│   ├── mail.php     # SMTP settings
│   └── cloudinary.php
├── controllers/     # Request handlers
├── models/          # Database models
├── views/           # UI templates
├── public/          # Static assets (css, js, images)
├── routes/          # Route definitions
├── uploads/         # User uploads
├── composer.json    # PHP dependencies
└── index.php        # Entry point
```

---

## Troubleshooting

### MongoDB Connection Failed

- Verify your Atlas credentials
- Check IP whitelist in MongoDB Atlas (allow 0.0.0.0/0 for local)
- Ensure `php.ini` has MongoDB extension enabled

### Session Errors

- Ensure `storage/` or `tmp/` directories are writable
- Check PHP session configuration

### Email Not Sending

- Verify SMTP credentials in `config/mail.php`
- Check that your email provider allows less secure apps or use app-specific passwords

---

## Security Notes

- Delete `setup.php` after initial setup
- Set `APP_ENV` to `production` in production environments
- Use strong database credentials
- Enable HTTPS in production
