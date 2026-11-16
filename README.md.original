# Company Management System (Render & Aiven Cloud Ready)

A modern, responsive Company Management System built with PHP 8.2, Apache, Bootstrap 4, and AdminLTE. Fully configured for zero-downtime deployment on **Render Web Services (Docker)** and **Aiven Cloud MySQL (SSL)**.

---

## 🚀 Live App Endpoints & Access

| Resource | Path / URL |
| :--- | :--- |
| **Public Website** | `https://<your-app>.onrender.com/` |
| **Admin Login** | `https://<your-app>.onrender.com/admin/login.php` |
| **1-Click DB Setup & Health Check** | `https://<your-app>.onrender.com/db_setup.php` |

---

## 🔐 Default Admin Credentials

- **Username:** `admin`
- **Password:** `admin123`
- **Role:** Administrator (ID: 1)

*(Credentials can be updated from the Admin Panel after initial login)*

---

## ☁️ Aiven Cloud MySQL Environment Variables

Configure these environment variables in your **Render Dashboard** (`Environment` tab) or via `render.yaml`:

| Environment Variable | Recommended / Example Value | Description |
| :--- | :--- | :--- |
| `PORT` | `10000` | Port assigned by Render |
| `DB_SERVER` | `mysql-xxxxx.aivencloud.com` | Aiven Cloud MySQL host |
| `DB_PORT` | `26284` | Aiven 5-digit custom MySQL port |
| `DB_USERNAME` | `avnadmin` | Aiven MySQL username |
| `DB_PASSWORD` | `<your-aiven-password>` | Aiven MySQL password |
| `DB_NAME` | `defaultdb` | Aiven database name |
| `DB_SSL` | `true` | Required by Aiven (`require_secure_transport=ON`) |
| `BASE_URL` | `https://<your-app>.onrender.com` | Public application URL |

---

## 📦 How to Deploy to Render

### Method 1: Blueprint Deployment (render.yaml)
1. Go to [Render Dashboard](https://dashboard.render.com/).
2. Click **New +** -> **Blueprint**.
3. Connect your repository: `https://github.com/maheshchouhanmsk12-sketch/Company-Management-System.git`.
4. Render will automatically detect `render.yaml`.
5. Enter your Aiven MySQL database credentials under Environment Variables.
6. Click **Apply**.

### Method 2: Manual Web Service
1. In Render Dashboard, click **New +** -> **Web Service**.
2. Select your GitHub repository.
3. Choose **Docker** as Runtime.
4. Add the Environment Variables listed above.
5. Click **Create Web Service**.

---

## ⚡ 1-Click Database Setup (`db_setup.php`)

Once your Render app is live:
1. Open `https://<your-app>.onrender.com/db_setup.php` in your browser.
2. The tool automatically performs a live health check on your Aiven MySQL instance with SSL enabled.
3. Click the **"Run 1-Click Database Import"** button.
4. The tool executes `database/company_website_db.sql` with `FOREIGN_KEY_CHECKS=0` and sets up all default tables and administrator accounts.
5. Click **"Go to Admin Login"** to start using your system!

---

## 🛠️ Key Technical Fixes & Improvements

1. **PHP 8.2 Compatibility:**
   - Fixed dynamic property declaration issues by safely initializing class properties inside `__construct()`.
   - Replaced deprecated function calls and guarded all `$conn->query()` and `$qry->fetch_assoc()` calls with `if ($qry && $qry instanceof mysqli_result)`.

2. **Aiven Cloud MySQL SSL & 5-Digit Port:**
   - Implemented `mysqli_init()` with `MYSQLI_CLIENT_SSL` flags to comply with Aiven's `require_secure_transport=ON`.
   - Support for 5-digit custom ports (e.g., `26284`).

3. **Render Port 10000 & Reverse Proxy Trailing Slash Fix:**
   - Apache dynamically binds to `$PORT` via `start.sh`.
   - Configured `UseCanonicalName Off`, `UseCanonicalPhysicalPort Off`, and `ServerName localhost` to prevent Apache from appending internal `:10000` port to redirects.
   - Updated frontend & login JavaScript redirects to use direct file routing (`admin/index.php`, `admin/login.php`) preventing directory slash redirects.
   - Configured `.htaccess` with smart port-stripping trailing slash rules.

4. **Safety & Backups:**
   - Original files backed up with `.original` extension before any changes were made.
