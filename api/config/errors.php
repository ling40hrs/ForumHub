<?php

declare(strict_types=1);

/**
 * Centralized error codes for ForumHub API.
 *
 * Frontend references these codes for error handling.
 * When adding a new error, add it here and update docs/api-contract.md.
 */

return [
    // Auth (100-199)
    'AUTH_INVALID_CREDENTIALS' => ['code' => 101, 'message' => 'Invalid email or password.', 'http' => 401],
    'AUTH_TOKEN_EXPIRED'       => ['code' => 102, 'message' => 'Token has expired.', 'http' => 401],
    'AUTH_TOKEN_INVALID'       => ['code' => 103, 'message' => 'Invalid token.', 'http' => 401],
    'AUTH_EMAIL_TAKEN'         => ['code' => 104, 'message' => 'Email already registered.', 'http' => 422],
    'AUTH_USERNAME_TAKEN'      => ['code' => 105, 'message' => 'Username already taken.', 'http' => 422],

    // Validation (200-299)
    'VALIDATION_REQUIRED'      => ['code' => 201, 'message' => 'This field is required.', 'http' => 422],
    'VALIDATION_TOO_SHORT'     => ['code' => 202, 'message' => 'Value is too short.', 'http' => 422],
    'VALIDATION_TOO_LONG'      => ['code' => 203, 'message' => 'Value is too long.', 'http' => 422],
    'VALIDATION_INVALID_EMAIL' => ['code' => 204, 'message' => 'Invalid email format.', 'http' => 422],

    // Resources (300-399)
    'RESOURCE_NOT_FOUND'       => ['code' => 301, 'message' => 'Resource not found.', 'http' => 404],
    'RESOURCE_FORBIDDEN'       => ['code' => 302, 'message' => 'You do not own this resource.', 'http' => 403],
    'RESOURCE_CONFLICT'        => ['code' => 303, 'message' => 'Resource already exists.', 'http' => 409],

    // Server (500-599)
    'SERVER_INTERNAL_ERROR'    => ['code' => 501, 'message' => 'Internal server error.', 'http' => 500],
    'SERVER_DATABASE_ERROR'    => ['code' => 502, 'message' => 'Database error.', 'http' => 500],
];
