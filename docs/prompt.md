REMOVE PUBLISHING REPORT MODULE COMPLETELY
FULL SYSTEM CLEANUP REQUIRED

IMPORTANT:
Completely DELETE the entire Publishing Report module from the project.

REMOVE EVERYTHING related to:

* frontend
* backend
* database
* AJAX
* routes
* sidebar
* permissions
* assignments
* APIs
* CSS
* JS
* SQL
* validations
* modals
* rendering
* save logic

THIS MUST BE A COMPLETE CLEAN REMOVAL.

==================================================
REMOVE FROM FRONTEND
====================

DELETE:

* Publishing Report button
* Publishing Report page
* Publishing Report modal
* Publishing Report tables
* row editor
* color picker
* assignment dropdown
* Add Row buttons
* SAVE button
* Publishing Report filters
* Publishing Report sections

==================================================
REMOVE FROM SIDEBAR
===================

DELETE:

* Publishing Report navigation item
* Publishing Report routes
* menu references

==================================================
REMOVE FROM JAVASCRIPT
======================

DELETE ALL:

* publishing-report.js
* publishing handlers
* dynamic row logic
* assignment logic
* color selection logic
* autosave logic
* report rendering logic
* AJAX calls
* event listeners

==================================================
REMOVE FROM BACKEND
===================

DELETE:

* save-report.php
* fetch-report.php
* assignment APIs
* report APIs
* row APIs
* cell APIs
* Publishing Report controllers
* helper functions
* report services

==================================================
REMOVE FROM DATABASE
====================

DELETE ALL TABLES RELATED TO:

* publishing_reports
* report_rows
* report_cells
* report_assignments
* publishing_templates
* recurring_reports

==================================================
REMOVE FOREIGN KEYS
===================

DELETE:

* foreign keys
* indexes
* constraints
* triggers
* relations

==================================================
SQL CLEANUP REQUIRED
====================

Create safe cleanup SQL.

DROP:

* tables
* indexes
* constraints
* orphan references

VERIFY:
no broken references remain.

==================================================
REMOVE FROM TASK FLOW
=====================

DELETE:

* Publishing Report task creation
* report-based task logic
* recurring report logic
* report assignment flow

==================================================
REMOVE FROM USER DASHBOARD
==========================

DELETE:

* report rendering
* assigned report sections
* report visibility logic
* report filtering

==================================================
REMOVE FROM ADMIN PANEL
=======================

DELETE:

* report management
* report assignment UI
* report permissions
* report actions

==================================================
REMOVE CSS
==========

DELETE:

* report table styles
* report colors
* report layouts
* report responsive rules

==================================================
REMOVE AJAX ENDPOINTS
=====================

DELETE:

* saveReport
* fetchReport
* assignReport
* updateCell
* updateColor
* createRow

==================================================
REMOVE VALIDATION LOGIC
=======================

DELETE:

* duplicate row validation
* assignment validation
* report validation
* report title validation

==================================================
REMOVE ROUTES
=============

DELETE:

* /publishing-report
* /save-report
* /fetch-report
* /assign-report

==================================================
REMOVE SESSION DATA
===================

DELETE:

* report session state
* report cache
* report temp state

==================================================
VERIFY CLEANUP
==============

After removal:
VERIFY:

1. no sidebar links remain
2. no console errors
3. no broken imports
4. no PHP include errors
5. no orphan SQL tables
6. no AJAX failures
7. no broken dashboard rendering
8. no dead routes

==================================================
IMPORTANT
=========

DO NOT:

* break Task Management
* break KPI system
* break Leave Management
* break authentication
* break dashboard

ONLY remove Publishing Report module.

==================================================
FINAL GOAL
==========

System should become:

* cleaner
* lighter
* stable
* maintainable

WITHOUT ANY Publishing Report code remaining anywhere in project.
