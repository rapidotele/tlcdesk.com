# TLCDesk - cPanel Installation Guide

## 1. Requirements
- PHP 8.1 or higher
- MySQL 5.7 or 8.0
- Apache with mod_rewrite enabled

## 2. Upload Files
1. Create a folder in your root (e.g., `tlcdesk_core`) outside of `public_html`.
2. Upload the `app`, `config`, `storage`, `database`, `bin` folders to `tlcdesk_core`.
3. Upload the contents of `public` to your `public_html` (or a subdirectory inside it).
4. Edit `public_html/index.php`:
   Change: `require __DIR__ . '/../app/bootstrap.php';`
   To: `require '/path/to/tlcdesk_core/app/bootstrap.php';`

## 3. Database
1. Go to cPanel > MySQL Database Wizard.
2. Create a database (e.g., `myuser_tlcdesk`).
3. Create a user and assign full privileges.

## 4. Install
1. Open your browser and go to `https://yourdomain.com/install`.
2. Enter the Database credentials.
3. Create your Super Admin account.
4. Click Install.

## 5. Cron Job (Background Jobs)
Go to cPanel > Cron Jobs. Add the following command to run every minute:

```bash
/usr/local/bin/php /path/to/tlcdesk_core/bin/console queue:work >> /path/to/tlcdesk_core/storage/logs/cron.log 2>&1
```
(Adjust the php path and project path accordingly).

## 6. Security
- Ensure `storage` directory is writable (`chmod -R 755 storage`).
- Block access to `.env` (handled by .htaccess in root, but ensure it is effective).
