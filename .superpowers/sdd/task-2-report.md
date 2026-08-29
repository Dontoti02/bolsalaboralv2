# Task 2 Report: Add backend queries in AdminController

## What was implemented

Added 3 new queries to the `dashboard()` method in `AdminController.php`:

1. **Monthly statistics** (`$monthlyStats`) — collects user, offer, and application counts per month for the last 6 months, with Spanish locale month labels.
2. **Role distribution** (`$roleDistribution`) — groups users by `rol_id` with label and color mapping for each role.
3. **Top 5 companies** (`$topCompanies`) — ranks companies by offer count, filtering out null company relations.

All three variables are:
- Passed to the view via `compact()` in the success path
- Initialized as empty collections in the catch block fallback
- Included in the fallback `compact()` call

## Files changed

- `app/Http/Controllers/AdminController.php` (+56 lines, -2 lines)

## Self-review findings

- All imports (`User`, `JobOpportunityOffer`, `JobOpportunityApplication`, `DB`) were already present — no new `use` statements needed.
- PHP lint passes with no syntax errors.
- Fallback values are consistent with the success path types (all `collect()`).
- No concerns.
