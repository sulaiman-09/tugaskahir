# TODO: Modify Dashboard to Show Top Subdistricts

## Tasks
- [x] Update DashboardController.php to query customer_leads table for top subdistricts
- [x] Parse subdistrict names from customer_address field
- [x] Calculate total registrations, covered, uncovered, and coverage rate per subdistrict
- [x] Order by total registrations descending, limit based on request param (default 10, options 10,25,50,100)
- [x] Update dashboard/index.blade.php to display Top Subdistricts table with specified columns
- [x] Add dropdown filter for display limit (10, 25, 50, 100)
- [x] Remove "+ Add Customer" button from dashboard
- [x] Test dashboard loads correctly with new data
