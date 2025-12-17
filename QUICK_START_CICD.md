# Quick CI/CD Setup Reference

## 🚀 Quick Start (5 minutes)

### 1. Run Setup Script
```bash
./setup-cicd.sh
```

This will:
- Generate SSH keys for deployment
- Show you what to copy to server
- Show you what to add to GitHub

### 2. Add Public Key to Server
```bash
ssh root@your-server-ip
cat >> ~/.ssh/authorized_keys << 'EOF'
[paste public key from setup script]
EOF
chmod 600 ~/.ssh/authorized_keys
```

### 3. Add GitHub Secrets

Go to: **GitHub Repository → Settings → Secrets → Actions → New secret**

Add these 4 secrets (values shown in setup script):
- `SSH_HOST` - Your server IP
- `SSH_USERNAME` - Usually `root`
- `SSH_PORT` - Usually `22`
- `SSH_PRIVATE_KEY` - The entire private key

### 4. Update Server Git Remote
```bash
ssh root@your-server-ip
cd /home/comfypace/htdocs/comfypace.com
git remote remove origin
git remote add origin https://github.com/hangaraku/cotha-center.git
mkdir -p /home/comfypace/backups
```

### 5. Deploy!
```bash
git add .
git commit -m "Setup CI/CD"
git push origin main
```

Watch the deployment in GitHub Actions tab! 🎉

---

## 📁 Files Created

- `.github/workflows/deploy.yml` - GitHub Actions workflow
- `deploy.sh` - Server deployment script
- `setup-cicd.sh` - Initial setup helper
- `DEPLOYMENT_GUIDE.md` - Full documentation
- `.gitignore` - Ignore sensitive files

---

## 🔍 Quick Commands

### Test SSH Connection
```bash
ssh -i ~/.ssh/github_deploy_key root@your-server-ip "echo 'Connected!'"
```

### Manual Deployment on Server
```bash
ssh root@your-server-ip
cd /home/comfypace/htdocs/comfypace.com
./deploy.sh
```

### Check Deployment Status
```bash
ssh root@your-server-ip
cd /home/comfypace/htdocs/comfypace.com
git log -1
php artisan --version
```

### View Laravel Logs
```bash
ssh root@your-server-ip
tail -f /home/comfypace/htdocs/comfypace.com/storage/logs/laravel.log
```

### List Backups
```bash
ssh root@your-server-ip
ls -lh /home/comfypace/backups/
```

---

## 🆘 Common Issues

### Deployment Failed?
1. Check GitHub Actions logs
2. SSH to server and check: `php artisan`
3. Check permissions: `chmod -R 755 storage bootstrap/cache`

### Site Stuck in Maintenance?
```bash
ssh root@your-server-ip
cd /home/comfypace/htdocs/comfypace.com
php artisan up
```

### Need to Rollback?
```bash
ssh root@your-server-ip
cd /home/comfypace/htdocs/comfypace.com
tar -xzf /home/comfypace/backups/backup-[timestamp].tar.gz
php artisan migrate
php artisan cache:clear
```

---

## 📖 Full Documentation

See `DEPLOYMENT_GUIDE.md` for complete details.
