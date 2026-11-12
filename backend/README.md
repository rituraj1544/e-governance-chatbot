# Laravel API

Laravel 13 API for SevaSetu. The root README contains the full Windows setup, MySQL, AdminSeeder, frontend, ML-service, and startup instructions.

Key implementation areas:

- `routes/api.php`: public, citizen, and administrator endpoints.
- `app/Services/ChatbotService.php`: rule-based matching, optional FastAPI intent classification, and MySQL retrieval.
- `app/Http/Middleware/EnsureAdmin.php`: server-side administrator authorization.
- `database/migrations/`: users, schemes, FAQs, chat history, and Sanctum tables.
- `database/seeders/SchemeFaqSeeder.php`: 10 source-linked service guides and 30 FAQs, with Hinglish variants on existing entries.
- `database/migrations/`: additive FAQ source URL and Hinglish fields; existing tables and catalogue rows are retained.

Local configuration uses the existing MySQL Workbench root connection and the `e_governance` database. Keep its actual password in the ignored `.env` file. Run `php artisan migrate` to add Hinglish fields and backfill the existing catalogue entries without inserting duplicates or resetting the database. Automated tests use in-memory SQLite.
