LEAVE MANAGEMENT SYSTEM FEATURE REQUIRED

IMPORTANT:
Update existing files if already created.
If file does not exist, create new file properly.

Update:

* frontend
* backend
* database
* staff dashboard
* admin dashboard
* AJAX logic
* notifications
* validation
* leave reports

Use existing purple glassmorphism SaaS UI system.

Add new sidebar menu item for STAFF dashboard:

* Leave Management

Position:
Below:

* My Profile

Icon:

* calendar / leave / vacation icon

Create new page:

Staff Access:

* staff only

Route Example:

* /staff/leave-management
* /staff/leaves

Create premium glassmorphism leave request form.

Fields:

1. Leave Type Dropdown
2. From Date
3. To Date
4. Total Days (auto calculate)
5. Leave Reason Textarea
6. Submit Button

Staff selects:

* From Date
* To Date

System automatically calculates:

* Total leave days

IMPORTANT:
Add proper validation everywhere.

1. Required field validation
2. Invalid date highlighting
3. Realtime validation
4. Loading states
5. Toast notifications
6. Inline error messages

1) Staff authentication required
2) Leave type required
3) From date required
4) To date required
5) To date cannot be before from date
6) No past invalid dates
7) Reason required
8) Reason minimum:
   10 characters
9) Reason maximum:
   1000 characters
10) Maximum leave days validation
11) Duplicate leave prevention

Staff cannot submit overlapping leave dates.

If overlapping leave exists:
Show error:

"Leave request already exists for selected dates."

Statuses:

* Pending
* Approved
* Rejected
* Cancelled

Default:
Pending

Staff can:

1. Create leave request
2. View leave history
3. View leave status
4. Cancel pending leave
5. Filter leaves by:

   * Pending
   * Approved
   * Rejected

Columns:

1. Leave Type
2. From Date
3. To Date
4. Total Days
5. Status Badge
6. Applied Date
7. Admin Comment
8. Actions

VERY IMPORTANT.

When staff submits leave request:

Admin dashboard should instantly show:

"New Leave Request"

Create admin leave management page.

Route Example:

* /admin/leave-management

Admin can:

1. View all leave requests
2. Filter by:

   * Pending
   * Approved
   * Rejected
   * Staff Member
3. Approve leave
4. Reject leave
5. Add admin comments
6. View leave calendar
7. View leave statistics

Columns:

1. Staff Name
2. Leave Type
3. From Date
4. To Date
5. Total Days
6. Reason
7. Status
8. Applied Date
9. Actions

Approve:

* Status = approved

Reject:

* Status = rejected

Add:

* admin comment
* rejection reason

Use AJAX polling every 10 seconds.

Admin should instantly see:

* new leave requests
* leave status changes
* pending approvals

Glassmorphism popup example:

"New Leave Request:
Keval requested leave from 12 May to 15 May."

Admin can:

* View request
* Approve
* Reject

Create new table:

Columns:

* id
* user_id
* leave_type
* from_date
* to_date
* total_days
* reason
* status
* admin_comment
* approved_by
* approved_at
* created_at
* updated_at

ENUM:

* pending
* approved
* rejected
* cancelled

Add indexes:

* user_id
* status
* from_date
* to_date

Create/Update APIs:

1. Create Leave Request
2. Update Leave Request
3. Cancel Leave Request
4. Get Staff Leaves
5. Get All Leave Requests
6. Approve Leave
7. Reject Leave

Use AJAX:

* submit leave without refresh
* realtime status updates
* instant validation
* live admin update

Use:

* glassmorphism cards
* purple gradients
* premium tables
* modern leave calendar
* floating summary cards
* animated notifications
* responsive layouts

Admin dashboard should show:

1. Total Leave Requests
2. Pending Leaves
3. Approved Leaves
4. Rejected Leaves
5. Monthly Leave Trend

* Staff can only access own leave requests
* Admin-only approval access
* CSRF protection
* SQL injection prevention
* XSS escaping
* Secure file uploads
* Role validation

Fully responsive:

* desktop
* tablet
* mobile

No overflow issues.

If file exists:

* UPDATE existing file

If file missing:

* CREATE new file

Do NOT duplicate logic.
Use reusable services and components.

Leave Management system should feel like:

* modern HR management SaaS
* enterprise employee portal
* premium productivity platform

NOT:

* basic leave form
* simple CRUD page
* outdated admin panel
