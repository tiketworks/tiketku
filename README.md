# tiket.ku - Centralized Event & Ticketing Platform

Event ticketing web app (PHP MVC + MySQL) shared by the Faculty of AI student organizations **PUFA** and **PUMA IS**.
Built for the ISAPMD Final Project.

## Roles

| Role | Can do |
| --- | --- |
| `user` (student) | Browse events, buy and cancel own tickets |
| `organizer` | Everything a user can, plus create / edit / delete **events of their own organization only** |
| `admin` (Faculty Admin) | Everything: all events, users, payments |

Details and test matrix: [docs/ROLE_ACCESS.md](docs/ROLE_ACCESS.md).

## Requirements
PHP 8.0+ with PDO MySQL, MySQL/MariaDB.

## Setup
1. Clone the repo and put the folder where your web server can serve it as `/event_ticketing/` (for XAMPP: `htdocs/event_ticketing`).
2. Create a database, then import in this order:
   ```bash
   mysql -u root -p tiketku < database/tiketku.sql
   mysql -u root -p tiketku < database/migrations/001_add_role_based_access.sql
   mysql -u root -p tiketku < database/migrations/002_align_organizers_with_charter.sql
   ```
3. Copy `config/local.example.php` to `config/local.php` and fill in your DB credentials
   (or set env vars `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).
4. Make sure `public/uploads/events/` is writable.
5. Open `http://localhost/event_ticketing/public/index.php`.

## Demo accounts
| Role | Email | Password |
| --- | --- | --- |
| admin | admin@tiketku.com | admin123 |
| organizer (PUFA, owns events 1 and 3) | organizer.pufa@tiketku.com | organizer123 |
| organizer (PUMA IS, owns events 2 and 4) | organizer.pumais@tiketku.com | organizer123 |
| user | budi@example.com | budi123 |

Seed passwords are upgraded to bcrypt hashes automatically on first login. Change these before any real deployment.

## Branching strategy
- `main`: stable, release-ready. Only updated by merging `dev` through a Pull Request.
- `dev`: integration branch. All work lands here through Pull Requests.
- `feature/<short-name>`, `fix/<short-name>`, `chore/<short-name>`: branch from `dev`, open a PR back into `dev`.
- Never push directly to `main` or `dev`. CI must pass before merging.

Commit messages follow `type: short description`, with types `feat`, `fix`, `docs`, `chore`, `test`, `refactor`.
Example: `feat: restrict event editing to owning organizer`.

## CI
`.github/workflows/ci.yml` runs a PHP syntax check on every push and pull request to `main` and `dev`.

## Team
| Role | Name |
| --- | --- |
| Product Owner / Business Analyst | Alvionita Nurul Kusuma |
| Scrum Master + DevOps / Release Coordinator | Naurah Ilvina |
| Development Lead | Citra Ramadhani Arisandi |
| QA / UAT Lead | Nelly Marito Sihombing |
