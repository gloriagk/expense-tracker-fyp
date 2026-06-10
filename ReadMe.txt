EXPENSE TRACKER SYSTEM

System Requirements
-------------------
PHP 8.2 or higher
Laragon
MySQL
Node.js

Installation Steps
------------------
1. Extract the project folder.
2. Create a database named "expense_tracker".
3. Import expense_tracker.sql into MySQL.
4. Copy .env.example and rename it to .env.
5. Configure database settings in .env.
6. Open terminal in project directory.
7. Run:

php artisan key:generate

8. Run:

php artisan migrate

9. Start the application:

php artisan serve

10. Open browser and visit:

http://127.0.0.1:8000

Project Features
----------------
- User Authentication
- Expense Management
- Budget Monitoring
- Dashboard Visualization
- K-Means Spending Analysis
- Personalized Financial Insights