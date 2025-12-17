# CI/CD Deployment Setup Guide

This guide explains how to set up automated deployment to your production server using GitHub Actions.

## 🚀 Overview

The CI/CD pipeline will automatically:
- ✅ Build frontend assets (npm run build)
- ✅ Install dependencies (composer & npm)
- ✅ Deploy code to remote server
- ✅ Run database migrations
- ✅ Clear and optimize caches
- ✅ Create automatic backups before deployment
- ✅ Enable maintenance mode during deployment

## 📋 Prerequisites

1. GitHub repository set up
2. SSH access to your remote server
3. Composer and Node.js installed on the server
4. Laravel application properly configured on server

## 🔧 Setup Instructions

### Step 1: Generate SSH Key Pair (if you don't have one)

On your local machine or server, generate an SSH key pair:

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_deploy_key
```

This creates:
- Private key: `~/.ssh/github_deploy_key`
- Public key: `~/.ssh/github_deploy_key.pub`

### Step 2: Add Public Key to Remote Server

Add the public key to your server's authorized keys:

```bash
# On your remote server
cat >> ~/.ssh/authorized_keys << 'EOF'
[paste your public key here from github_deploy_key.pub]
EOF

# Set correct permissions
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

Or copy it directly:
```bash
ssh-copy-id -i ~/.ssh/github_deploy_key.pub root@your-server-ip
```

### Step 3: Configure GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions → New repository secret

Add the following secrets:

#### Required Secrets:

1. **SSH_HOST**
   - Your server IP or domain
   - Example: `123.456.789.0` or `comfypace.com`

2. **SSH_USERNAME**
   - Your SSH username
   - Example: `root`

3. **SSH_PRIVATE_KEY**
   - Your private SSH key content
   - Copy the entire content of `~/.ssh/github_deploy_key`
   ```bash
   cat ~/.ssh/github_deploy_key
   ```
   - Paste the entire output (including `-----BEGIN OPENSSH PRIVATE KEY-----` and `-----END OPENSSH PRIVATE KEY-----`)

4. **SSH_PORT** (optional, default is 22)
   - SSH port number
   - Example: `22` or `2222`

### Step 4: Prepare Remote Server

SSH into your server and ensure everything is ready:

```bash
# Connect to your server
ssh root@your-server-ip

# Navigate to project directory
cd /home/comfypace/htdocs/comfypace.com

# Remove old git remote (since you changed accounts)
git remote remove origin

# Ensure correct permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Create backups directory
mkdir -p /home/comfypace/backups

# Make sure composer and npm are available
which composer
which npm
which php

# Verify PHP version (should be 8.1+)
php -v

# Verify Node version (should be 18+)
node -v
```

### Step 5: Update Git Remote on Server

The deployment script will automatically update the git remote, but you can do it manually:

```bash
cd /home/comfypace/htdocs/comfypace.com
git remote remove origin
git remote add origin https://github.com/hangaraku/cotha-center.git
git fetch origin main
```

### Step 6: Test SSH Connection

Before deploying, test the SSH connection from your local machine:

```bash
ssh -i ~/.ssh/github_deploy_key root@your-server-ip "cd /home/comfypace/htdocs/comfypace.com && pwd"
```

Should output: `/home/comfypace/htdocs/comfypace.com`

## 🎯 How to Deploy

### Automatic Deployment

Simply push to the `main` branch:

```bash
git add .
git commit -m "Your commit message"
git push origin main
```

The deployment will start automatically!

### Manual Deployment

You can also trigger deployment manually:

1. Go to your GitHub repository
2. Click on "Actions" tab
3. Select "Deploy to Production Server" workflow
4. Click "Run workflow" button
5. Select the branch and click "Run workflow"

## 📊 Monitoring Deployment

### Watch Deployment Progress

1. Go to GitHub repository → Actions tab
2. Click on the running workflow
3. Watch real-time logs

### Check Deployment on Server

SSH into your server and check:

```bash
# Check if maintenance mode is off
php artisan | grep -i down

# Check latest git commit
cd /home/comfypace/htdocs/comfypace.com
git log -1

# Check Laravel logs
tail -f storage/logs/laravel.log

# Check application status
php artisan about
```

## 🔄 Deployment Process

The workflow performs these steps in order:

1. **Checkout code** - Gets latest code from repository
2. **Setup PHP & Node.js** - Prepares build environment
3. **Install dependencies** - Runs `composer install` and `npm ci`
4. **Build assets** - Runs `npm run build`
5. **Create backup** - Backs up current deployment
6. **Enable maintenance mode** - Shows maintenance page to users
7. **Update git remote** - Switches to new repository
8. **Pull changes** - Gets latest code via git
9. **Install server dependencies** - Updates composer & npm on server
10. **Build on server** - Rebuilds assets on server
11. **Clear caches** - Clears all Laravel caches
12. **Run migrations** - Updates database schema
13. **Optimize** - Caches configs, routes, and views
14. **Fix permissions** - Sets correct file permissions
15. **Disable maintenance mode** - Brings site back online

## 🔒 Security Best Practices

1. **Never commit secrets** - Use GitHub Secrets for sensitive data
2. **Use SSH keys** - Don't use passwords for deployment
3. **Restrict SSH access** - Consider IP whitelisting
4. **Keep backups** - The script keeps last 5 backups automatically
5. **Review changes** - Always review code before pushing to main

## 🐛 Troubleshooting

### Deployment Fails

1. Check GitHub Actions logs for error messages
2. SSH into server and check:
   ```bash
   cd /home/comfypace/htdocs/comfypace.com
   git status
   php artisan
   ```

### Permission Errors

```bash
# On server
cd /home/comfypace/htdocs/comfypace.com
sudo chmod -R 755 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Git Conflicts

```bash
# On server
cd /home/comfypace/htdocs/comfypace.com
git reset --hard origin/main
```

### Site Stuck in Maintenance Mode

```bash
# On server
cd /home/comfypace/htdocs/comfypace.com
php artisan up
```

### Restore from Backup

```bash
# List backups
ls -lh /home/comfypace/backups/

# Restore backup (replace with your backup file)
cd /home/comfypace/htdocs/comfypace.com
tar -xzf /home/comfypace/backups/backup-20241217-120000.tar.gz

# Run migrations and clear cache
php artisan migrate
php artisan cache:clear
php artisan config:cache
```

## 📝 Workflow File Location

The workflow file is located at:
```
.github/workflows/deploy.yml
```

## 🔧 Customization

### Change Deployment Branch

Edit `.github/workflows/deploy.yml`:

```yaml
on:
  push:
    branches:
      - production  # Change from 'main' to your branch
```

### Skip Migrations

Edit the workflow file and comment out or remove:

```yaml
php artisan migrate --force
```

### Add Slack/Discord Notifications

Add notification steps at the end of the workflow using appropriate GitHub Actions.

## 📞 Support

If you encounter issues:

1. Check GitHub Actions logs
2. Check server Laravel logs: `storage/logs/laravel.log`
3. Check web server logs (nginx/apache)
4. Verify all secrets are correctly configured in GitHub

## ✅ Verification Checklist

Before your first deployment:

- [ ] SSH keys generated and added to server
- [ ] All GitHub secrets configured
- [ ] Server has git, composer, npm, and PHP installed
- [ ] Project directory exists on server
- [ ] Permissions are set correctly
- [ ] .env file exists on server
- [ ] Database is accessible
- [ ] Test SSH connection works
- [ ] Backups directory created

Once everything is set up, your deployments will be automatic! 🎉
