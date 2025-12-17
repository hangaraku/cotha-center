#!/bin/bash

# Laravel Deployment Script
# This script handles the deployment process on the remote server

set -e # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR="/home/comfypace/htdocs/comfypace.com"
BACKUP_DIR="/home/comfypace/backups"
MAX_BACKUPS=5

# Functions
print_status() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

# Navigate to project directory
cd "$PROJECT_DIR" || exit 1

print_status "Starting deployment process..."

# Check PHP version
print_status "Checking PHP version..."
PHP_VERSION=$(php -r "echo PHP_VERSION;")
print_status "Server PHP version: $PHP_VERSION"

# Check if PHP version is compatible (requires 8.3+)
PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")
if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 3 ]); then
    print_error "PHP 8.3 or higher is required. Current version: $PHP_VERSION"
    print_warning "Please upgrade PHP on your server."
    exit 1
fi

# Create backup
print_status "Creating backup..."
BACKUP_FILE="$BACKUP_DIR/backup-$(date +%Y%m%d-%H%M%S).tar.gz"
tar -czf "$BACKUP_FILE" \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.git' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    . || print_warning "Backup creation had warnings"

print_status "Backup created: $BACKUP_FILE"

# Keep only last N backups
print_status "Cleaning old backups (keeping last $MAX_BACKUPS)..."
ls -t "$BACKUP_DIR"/backup-*.tar.gz | tail -n +$((MAX_BACKUPS + 1)) | xargs -r rm

# Enable maintenance mode
print_status "Enabling maintenance mode..."
php artisan down --retry=60 || print_warning "Could not enable maintenance mode"

# Update git remote and pull changes
print_status "Updating git configuration..."
git remote remove origin 2>/dev/null || true
git remote add origin https://github.com/hangaraku/cotha-center.git

print_status "Pulling latest changes from main branch..."
git fetch origin main
git reset --hard origin/main

# Install Composer dependencies
print_status "Installing Composer dependencies..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Install NPM dependencies
print_status "Installing NPM dependencies..."
npm ci

# Build assets
print_status "Building frontend assets..."
npm run build

# Clear caches
print_status "Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run database migrations
print_status "Running database migrations..."
php artisan migrate --force

# Optimize application
print_status "Optimizing application for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set correct permissions
print_status "Setting correct permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Disable maintenance mode
print_status "Disabling maintenance mode..."
php artisan up

print_status "Deployment completed successfully! ✅"

# Display summary
echo ""
echo "================================"
echo "Deployment Summary"
echo "================================"
echo "Project: $PROJECT_DIR"
echo "Backup: $BACKUP_FILE"
echo "Timestamp: $(date +'%Y-%m-%d %H:%M:%S')"
echo "================================"
