KPI DAILY SCORING SYSTEM UPDATE REQUIRED

IMPORTANT:
Update existing KPI system with DAILY scoring workflow.

If file exists:

* UPDATE existing file

If file missing:

* CREATE new file

Update:

* frontend
* backend
* database
* validation
* reports
* monthly calculations
* KPI analytics

Use existing purple glassmorphism SaaS UI system.

Admin will add KPI score DAILY for every staff member.

System must automatically:

* store daily KPI records
* calculate monthly score
* generate overall performance analytics

Admin Flow:

1. Open KPI Management
2. Select Staff Member
3. Select Date
4. KPI scoring table opens
5. Admin gives daily scores
6. Save daily KPI

Columns:

1. KPI Type
2. Weight
3. Daily Score
4. Weighted Score
5. Notes

1) Productivity
   Weight:
   30%

2) Quality
   Weight:
   25%

3) Discipline
   Weight:
   15%

4) Communication (Standup Meeting)
   Weight:
   15%

5) Growth & Ownership
   Weight:
   15%

Score Range:
0 to 10 only

Examples:

* 0
* 5
* 7
* 10

IMPORTANT:
Add proper validation everywhere.

1. Staff member required
2. Date required
3. No future date allowed
4. Score required
5. Score must be numeric
6. Score must be between 0 and 10
7. Notes max length:
   1000 characters
8. Admin only access
9. Duplicate daily KPI prevention

Admin cannot add KPI twice for:

* same staff
* same date

If duplicate exists:
Show error:

"KPI score already added for this staff member on selected date."

Add:

* inline validation
* realtime validation
* invalid field highlighting
* toast notifications
* loading states

System automatically calculates:

1. Daily KPI Average
2. Monthly KPI Average
3. Overall Monthly Score
4. Salary Approval %
5. Performance Status

Admin can select staff member and month.

Then system shows:

1. Daily KPI history
2. Monthly average
3. Total KPI %
4. Salary approval %
5. Performance trend chart
6. Overall performance status

90-100:
Excellent

75-89:
Good

60-74:
Average

40-59:
Needs Improvement

0-39:
Critical

Add KPI analytics cards:

1. Highest Performing Staff
2. Lowest Performing Staff
3. Average Team KPI
4. Monthly KPI Trend
5. Staff Performance Ranking

Admin can filter KPI reports by:

* Daily
* Weekly
* Monthly
* 3 Months
* 6 Months
* 12 Months

Create dedicated page:

Example:

* /admin/kpi/staff-report?id=staff_id

Show:

1. Staff profile card
2. Monthly KPI graph
3. Daily KPI table
4. Overall score
5. Salary recommendation %
6. Performance badge
7. Admin notes history

Update existing KPI table structure.

Columns:

* id
* user_id
* kpi_date
* productivity_score
* quality_score
* discipline_score
* communication_score
* growth_score
* weighted_total_score
* salary_approval_percentage
* performance_status
* admin_notes
* created_by
* created_at
* updated_at

Add indexes:

* user_id
* kpi_date
* created_by

Add UNIQUE constraint:
(user_id, kpi_date)

Create/Update APIs:

1. Add Daily KPI
2. Update Daily KPI
3. Get Monthly KPI
4. Get Staff Overall KPI
5. KPI Analytics Summary
6. KPI Ranking Report

Use AJAX:

* save KPI without refresh
* realtime calculations
* dynamic monthly analytics
* instant validation

Use:

* glassmorphism cards
* premium KPI tables
* purple gradients
* animated score counters
* modern analytics charts
* responsive layouts

- Admin-only middleware
- CSRF validation
- SQL injection prevention
- XSS escaping
- Secure AJAX requests
- Role validation

Staff MUST NOT:

* access KPI routes
* see KPI sidebar
* see KPI reports
* modify KPI data

Admin only access.

KPI system should feel like:

* enterprise employee evaluation platform
* modern HR analytics dashboard
* premium performance tracking SaaS

NOT:

* simple spreadsheet
* static score form
* basic CRUD system
