# Taskly - Enterprise Task & Performance SaaS

Taskly is a premium, high-performance **Task Management & Team KPI Analytics** system. Engineered for high-output teams, it combines project tracking with smart automation and data-driven performance metrics.

---

## ✨ Key Features

### 🛠️ Project & Task Lifecycle
- **Dynamic Board View**: Manage tasks with a modern, glassmorphic card interface.
- **Project Progress Analytics**: Real-time velocity tracking and milestone monitoring.
- **Role-Based Access (RBAC)**: Secure multi-tier access for Admins and Staff.

### 🤖 Smart Automation
- **Recurring Tasks**: Automate weekly and monthly deliverables with parent-child task logic.
- **Activity Logs**: Full audit trail for every action taken within the system.
- **Deadline Alerts**: Automated status monitoring for overdue and high-priority tasks.

### 📊 Performance & HR
- **KPI Merit System**: Daily scoring console with weighted averages and performance ranking.
- **Leave Management**: Full HR suite for leave applications, review, and history tracking.
- **Analytics Dashboards**: Visual insights into team growth and operational efficiency.

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Backend** | PHP 8.2+ (Core MVC Architecture) |
| **Database** | MySQL 8.0+ (InnoDB, JSON Metadata) |
| **Frontend** | Vanilla CSS3, Bootstrap 5.3, FontAwesome 6 |
| **Logic** | jQuery 3.7, Moment.js, DataTables |
| **Auth** | PDO-based Secure Session Management |

---

## 🚀 Quick Start

### 📋 Prerequisites
- **Server**: XAMPP / WAMP / MAMP (PHP 8.2+)
- **Database**: MySQL / MariaDB

### ⚙️ Installation
1. **Clone the Repo**
   ```bash
   git clone https://github.com/your-username/Task_Management.git
   ```
2. **Database Setup**
   - Create a database: `task_management`
   - Import `database/schema.sql`
   - Or run `database/init_db.php` for automatic setup.
3. **Environment Config**
   - Copy `.env.example` to `.env`
   - Update your DB credentials.
4. **Launch**
   - Navigate to `http://localhost/Task_Management`
   - Default Admin: `admin` / `password123`

---

## 📁 Architecture Overview

```text
├── app/
│   ├── core/         # Framework foundations
│   ├── controllers/  # Request handlers
│   ├── models/       # Data layer
│   └── views/        # UI templates
├── database/
│   ├── schema.sql    # Master schema
│   └── migrations/   # Feature updates
├── public/
│   ├── assets/       # CSS/JS design tokens
│   └── index.php     # Entry point
└── routes/           # System routing
```

---

## 🎨 Design System
Taskly uses a custom-built **SaaS Design Token** system defined in `style.css` and `tokens.css`. 
- **Pill UI Components**: Standardized 52px heights for all form elements.
- **Glassmorphism**: Modern, transparent card overlays with subtle shadows.
- **Enterprise Palette**: Curated slate and indigo color scales for reduced eye strain.

---

## 📄 License
Distributed under the MIT License. See `LICENSE` for more information.

---
*Built with ❤️ for High-Performance Teams.*
