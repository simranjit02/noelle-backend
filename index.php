<?php
// This file is no longer used when using .router.php
// The router (.router.php) handles all requests
// Keep this for backward compatibility if needed

// CORS and routing handled by .router.php
http_response_code(404);
echo json_encode(['error' => 'Use .router.php instead']);
?>
