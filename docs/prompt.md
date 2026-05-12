DASHBOARD + TASK PRIORITY SYSTEM UPDATE REQUIRED

IMPORTANT:
Update existing files if already created.
If file does not exist, create new file properly.

Update:

* frontend
* backend
* database
* AJAX logic
* notifications
* dashboard UI
* responsive behavior

Use existing project structure and documentation.

Current dashboard layout is not optimized.

REQUIRED CHANGES:

1. Move "Task Priority" section from RIGHT SIDE to LEFT SIDE.

2. Move "Growth Analysis / Graph" section BELOW Task Priority.

New Layout Structure:

TOP:

* Statistics Cards

MIDDLE LEFT:

* Task Priority Card

MIDDLE RIGHT:

* Recent Assignments / Analytics Summary

BOTTOM:

* Growth Analysis Graph

Create a FULL task priority management system.

Priority Types:

* High
* Medium
* Low

When admin clicks any priority item:

Example:

* High Priority
* Medium Priority
* Low Priority

Then show:

1. Which staff member is assigned
2. Project name
3. Task name
4. Due date
5. Task status
6. Completion checkbox
7. Incomplete checkbox

Create modern glassmorphism modal popup.

Modal must contain:

* Task details
* Staff avatar/name
* Priority badge
* Due date
* Status badge
* Complete checkbox
* Incomplete checkbox

UI Style:

* Purple glassmorphism
* Rounded modern cards
* Smooth animations
* Responsive

Staff dashboard should use SAME layout pattern.

When staff clicks Task Priority:

Show:

* Their assigned tasks only
* Priority level
* Due date
* Task status

Add:

* Complete checkbox
* Incomplete checkbox

Checkbox Rules:

1. If Complete checked:

* Update task status = completed
* Save completed_at timestamp
* Update admin dashboard instantly

2. If Incomplete checked:

* Status = incomplete
* Trigger admin popup notification
* Store alert in database

VERY IMPORTANT FEATURE.

If staff marks task as incomplete due date and time then after :

Admin dashboard should show CONTINUOUS popup notification until admin acknowledges.

Popup Example:
"Task Incomplete Alert:
AI Video Editing task assigned to Keval is marked incomplete."

Features:

* Realtime-like polling every 10 seconds using AJAX
* Notification sound optional
* Glassmorphism popup UI
* Admin can:

  * View task
  * Dismiss alert
  * Reassign task

Update database schema properly.

If table exists:

* ALTER table safely

If not:

* CREATE new table

TASKS TABLE:
Add:

* priority ENUM('high','medium','low')
* is_completed TINYINT(1)
* is_incomplete TINYINT(1)
* completed_at TIMESTAMP NULL
* admin_alert_sent TINYINT(1)

==================================================
CREATE TABLE:
task_alerts

Columns:

* id
* task_id
* user_id
* message
* is_read
* created_at
* updated_at

Create/Update:

1. Task Priority APIs
2. Task status update APIs
3. Admin alert APIs
4. AJAX polling APIs
5. Notification handlers

1) Get Priority Tasks
2) Update Task Status
3) Get Incomplete Alerts
4) Mark Alert Read
5) Reassign Task

Update dashboard UI:

* responsive
* glassmorphism
* purple gradients
* smooth hover
* modern charts

Add:

* interactive priority cards
* popup modals
* animated notifications
* live task updates
* loading skeletons

Use:

* AJAX polling every 10 seconds
* No page refresh

Admin should instantly see:

* incomplete task alerts
* completed tasks
* updated priorities

- Validate all AJAX requests
- CSRF protection
- Role validation
- Staff can only update assigned tasks
- Admin-only alert management

* Light theme only
* Premium SaaS look
* Purple glassmorphism
* No overflow issues
* Fully responsive
* Mobile/tablet support
* Smooth animations
* No old Bootstrap styling

If file exists:

* UPDATE existing file

If file missing:

* CREATE new file

Do NOT duplicate logic.
Use reusable components and services.

Dashboard should feel like:

* premium SaaS
* modern agency management system
* realtime productivity platform

NOT:

* basic admin template
* static dashboard
* simple CRUD panel
