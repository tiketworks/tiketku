# Role-Based Access

Permissions are enforced in controllers through `helpers/Auth.php`. The UI only hides buttons; the server-side check is what protects the data.

Data model: `users.role` (`admin` | `organizer` | `user`) and `users.organizer_id` (FK to `organizers.id`, only set for organizers).

## Rules
- Self-registration always creates `role = user`. Roles can only be changed in the database.
- An organizer's `organizer_id` is taken from the session, never from the submitted form.
- Only admin can move an event to a different organizer.
- Opening a forbidden page redirects to the event list with the message "You do not have permission to do that."

## Test matrix (verified)
Seed data (after migrations 001 and 002): events 1 and 3 belong to PUFA (organizer 1), events 2 and 4 to PUMA IS (organizer 2).

| # | Login as | Action | Expected |
| --- | --- | --- | --- |
| 1 | organizer.pufa | Open edit page of event 1 | Form shown |
| 2 | organizer.pufa | Open edit page of event 2 | Redirect, forbidden message |
| 3 | organizer.pufa | Delete event 2 (direct URL) | Redirect, event still exists |
| 4 | organizer.pufa | Create event, form tampered to `organizer_id=2` | Event saved with organizer 1 (PUFA) |
| 5 | organizer.pufa | Edit event 1, form tampered to `organizer_id=2` | Event stays with organizer 1 (PUFA) |
| 6 | organizer.pufa | Open Users or Payment page | Redirect to event list |
| 7 | organizer.pufa | Event detail of event 2 | No Edit/Delete buttons |
| 8 | budi (user) | Open create or edit event | Redirect, forbidden message |
| 9 | admin | Edit any event, open Users page | Allowed |
| 10 | (register) | Register with tampered `role=admin` | Account created as `user` |
| 11 | any | Login with wrong password | "Invalid email or password." |
