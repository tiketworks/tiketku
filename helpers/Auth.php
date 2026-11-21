<?php
/**
 * Central role-based access helper.
 * Roles: admin (everything), organizer (own events only), user (buy tickets).
 * All permission checks must go through this class so rules live in one place.
 */
class Auth {
    const ADMIN     = 'admin';
    const ORGANIZER = 'organizer';
    const USER      = 'user';

    public static function role(): string {
        return $_SESSION['role'] ?? 'guest';
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool {
        return self::role() === self::ADMIN;
    }

    public static function isOrganizer(): bool {
        return self::role() === self::ORGANIZER && self::organizerId() !== null;
    }

    /** Admin or organizer: may open the create-event page. */
    public static function isEventManager(): bool {
        return self::isAdmin() || self::isOrganizer();
    }

    public static function organizerId(): ?int {
        $id = $_SESSION['organizer_id'] ?? null;
        return ($id === null || $id === '') ? null : (int) $id;
    }

    /** Admin may manage any event; organizer only events of their own organization. */
    public static function canManageEvent($eventOrganizerId): bool {
        if (self::isAdmin()) {
            return true;
        }
        return self::isOrganizer() && (int) $eventOrganizerId === self::organizerId();
    }

    public static function redirect(string $query): void {
        header('Location: /event_ticketing/public/index.php?' . $query);
        exit;
    }
}
