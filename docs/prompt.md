PIN TASK UI DESIGN UPDATE

IMPORTANT:
Current pin task design is incorrect.

Pinned tasks should NOT appear like normal todo rows.

When:
admin selects "Pin Task"

AND:
creates todo

THEN:
show task inside dedicated stacked boxes.

Pinned task section should look like:

---

+----------------------------------+
| Pin Task Title                   || Date + Time                      |
+----------------------------------+

+----------------------------------+
| Pin Task Title                   || Date + Time                      |
+----------------------------------+

+----------------------------------+
| Pin Task Title                   || Date + Time                      |
+----------------------------------+

---

ONLY SHOW:

* Task Title
* Date
* Time

DO NOT SHOW:

* assigned user
* status
* buttons
* priority
* description
* notes
* progress

Each pin task card:

* full width
* medium height
* rounded corners
* soft border
* glassmorphism light effect
* subtle purple left border
* spacing between cards
* hover animation

Create dedicated section:

Pinned Tasks

Position:
TOP of Todo page.

Normal todos remain separate below.

Example:

18 May 2026 10:30 AM

Use existing:
created_at timestamp.

No extra fields needed.

Desktop:
stack vertically

Tablet:
full width cards

Mobile:
compact cards

Use:
modern SaaS productivity app design.

Do NOT:
change existing todo logic.

Only:
change pinned task rendering UI.

Pinned tasks should feel like:

* sticky reminders
* always visible notes
* lightweight work instructions

NOT like:
task table rows.
