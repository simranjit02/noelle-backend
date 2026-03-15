#!/bin/bash

# Noelle Backend Startup Script
# Run: ./start-server.sh

echo "Starting Noelle Backend API..."
echo "================================"
echo ""
echo "✓ PHP Server: http://localhost:5000"
echo "✓ Using Router: .router.php"
echo "✓ CORS: Enabled for all origins"
echo ""
echo "Press Ctrl+C to stop"
echo ""

php -S localhost:5000 .router.php
