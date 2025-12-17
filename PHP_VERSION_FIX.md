# PHP Version Compatibility Fix

## Issue
The deployment failed because your composer.lock file requires PHP 8.3+, but the server or CI environment was using PHP 8.2.

## What Was Changed

### 1. GitHub Actions Workflow (`.github/workflows/deploy.yml`)
- ✅ Updated PHP version from 8.2 to 8.3
- ✅ Removed composer install from CI (will run on server with correct PHP version)
- ✅ Added PHP version check in deployment script
- ✅ Kept npm build in CI (works fine)

### 2. Deployment Script (`deploy.sh`)
- ✅ Added PHP version validation
- ✅ Will exit with error if PHP < 8.3

## Required Server Setup

Your production server **MUST have PHP 8.3 or higher** installed.

### Check Current PHP Version on Server

```bash
ssh root@your-server
php -v
```

### If You Need to Upgrade PHP (Ubuntu/Debian)

```bash
# SSH to your server
ssh root@your-server

# Add Ondřej Surý's PPA (if not already added)
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Install PHP 8.3
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring \
                 php8.3-curl php8.3-gd php8.3-intl php8.3-zip php8.3-bcmath

# Set PHP 8.3 as default
sudo update-alternatives --set php /usr/bin/php8.3

# Verify
php -v
# Should show: PHP 8.3.x

# Restart web server
sudo systemctl restart nginx  # or apache2
sudo systemctl restart php8.3-fpm
```

### Alternative: Use Different PHP Version for CLI

If you have multiple PHP versions and want to specify which one to use:

```bash
# Update deploy.sh to use specific PHP binary
# Replace all instances of 'php' with '/usr/bin/php8.3'

# Or create an alias in .bashrc
echo "alias php='/usr/bin/php8.3'" >> ~/.bashrc
source ~/.bashrc
```

### Update Composer on Server

After upgrading PHP, update Composer:

```bash
ssh root@your-server
cd /home/comfypace/htdocs/comfypace.com

# Update Composer itself
composer self-update

# Clear Composer cache
composer clear-cache

# Install dependencies
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
```

## Alternative Solution: Downgrade Packages (Not Recommended)

If you cannot upgrade PHP on the server, you can downgrade the problematic packages:

```bash
# On your LOCAL machine (not server)
composer require openspout/openspout:^4.24 --with-all-dependencies
composer require symfony/css-selector:^7.0 --with-all-dependencies
composer update --lock

git add composer.lock
git commit -m "Downgrade packages for PHP 8.2 compatibility"
git push origin main
```

**WARNING:** This may break features or introduce security issues. Upgrading PHP is strongly recommended.

## Why This Happened

Your local development environment uses PHP 8.5, so when you ran `composer update`, it locked packages that require PHP 8.3+. The CI environment was using PHP 8.2, which is incompatible.

## Verification Steps

After fixing:

1. **Verify server PHP version:**
   ```bash
   ssh root@your-server "php -v"
   ```
   Should show 8.3 or higher

2. **Test composer install:**
   ```bash
   ssh root@your-server "cd /home/comfypace/htdocs/comfypace.com && composer install --no-dev"
   ```
   Should complete without errors

3. **Push code to trigger deployment:**
   ```bash
   git add .
   git commit -m "Fix PHP version compatibility"
   git push origin main
   ```

4. **Watch GitHub Actions:**
   - Go to GitHub → Actions tab
   - Watch the deployment run
   - Should complete successfully ✅

## Summary of Required PHP Versions

- **Local Development:** PHP 8.3+ (you have 8.5 ✅)
- **GitHub Actions CI:** PHP 8.3+ (updated ✅)
- **Production Server:** PHP 8.3+ (needs to be verified/upgraded ⚠️)

## Next Steps

1. SSH to your server and check PHP version
2. If PHP < 8.3, upgrade it using instructions above
3. Test composer install on server
4. Push code to trigger new deployment
5. Verify deployment succeeds

## Support

If you encounter issues:
- Check server PHP version: `php -v`
- Check which PHP binary is used: `which php`
- List all PHP versions: `ls /usr/bin/php*`
- Check web server PHP version: Create `phpinfo.php` file
