e#!/bin/bash

# Quick Setup Script for CI/CD Deployment
# Run this script to generate SSH keys and get setup instructions

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  CI/CD Deployment Setup Helper${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Check if SSH key already exists
KEY_PATH="$HOME/.ssh/github_deploy_key"

if [ -f "$KEY_PATH" ]; then
    echo -e "${YELLOW}⚠️  SSH key already exists at $KEY_PATH${NC}"
    read -p "Do you want to use the existing key? (y/n): " use_existing
    if [ "$use_existing" != "y" ]; then
        echo -e "${RED}Please remove or backup the existing key first.${NC}"
        exit 1
    fi
else
    # Generate SSH key
    echo -e "${GREEN}Generating SSH key pair...${NC}"
    ssh-keygen -t ed25519 -C "github-actions-deploy" -f "$KEY_PATH" -N ""
    echo -e "${GREEN}✅ SSH key pair generated!${NC}"
    echo ""
fi

# Display public key
echo -e "${BLUE}========================================${NC}"
echo -e "${YELLOW}📋 PUBLIC KEY (Add this to your server)${NC}"
echo -e "${BLUE}========================================${NC}"
cat "${KEY_PATH}.pub"
echo ""
echo -e "${BLUE}========================================${NC}"
echo ""

# Display private key for GitHub
echo -e "${YELLOW}🔐 PRIVATE KEY (Add this to GitHub Secrets)${NC}"
echo -e "${BLUE}========================================${NC}"
cat "$KEY_PATH"
echo ""
echo -e "${BLUE}========================================${NC}"
echo ""

# Get server details
read -p "Enter your server IP or domain: " server_host
read -p "Enter your SSH username (default: root): " ssh_user
ssh_user=${ssh_user:-root}
read -p "Enter your SSH port (default: 22): " ssh_port
ssh_port=${ssh_port:-22}

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Setup Instructions${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

echo -e "${YELLOW}1. Add Public Key to Server${NC}"
echo "   Run this command on your server:"
echo ""
echo -e "${BLUE}cat >> ~/.ssh/authorized_keys << 'EOF'"
cat "${KEY_PATH}.pub"
echo "EOF"
echo "chmod 600 ~/.ssh/authorized_keys"
echo "chmod 700 ~/.ssh${NC}"
echo ""

echo -e "${YELLOW}2. Configure GitHub Secrets${NC}"
echo "   Go to: GitHub Repository → Settings → Secrets and variables → Actions"
echo "   Add these secrets:"
echo ""
echo "   SSH_HOST:"
echo "   ${server_host}"
echo ""
echo "   SSH_USERNAME:"
echo "   ${ssh_user}"
echo ""
echo "   SSH_PORT:"
echo "   ${ssh_port}"
echo ""
echo "   SSH_PRIVATE_KEY:"
echo "   (Copy the entire private key shown above)"
echo ""

echo -e "${YELLOW}3. Test SSH Connection${NC}"
echo "   Run this command to test:"
echo ""
echo -e "${BLUE}ssh -i $KEY_PATH -p $ssh_port ${ssh_user}@${server_host} 'echo \"Connection successful!\"'${NC}"
echo ""

echo -e "${YELLOW}4. Prepare Server${NC}"
echo "   SSH into your server and run:"
echo ""
echo -e "${BLUE}cd /home/comfypace/htdocs/comfypace.com
git remote remove origin
git remote add origin https://github.com/hangaraku/cotha-center.git
mkdir -p /home/comfypace/backups
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache${NC}"
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Next Steps${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "1. Copy the public key to your server (shown above)"
echo "2. Add the secrets to GitHub (shown above)"
echo "3. Test the SSH connection"
echo "4. Prepare the server with the commands shown"
echo "5. Push to main branch to trigger deployment!"
echo ""
echo -e "${GREEN}For detailed instructions, see: DEPLOYMENT_GUIDE.md${NC}"
echo ""

# Save configuration to file
CONFIG_FILE=".deployment-config"
cat > "$CONFIG_FILE" << EOF
# Deployment Configuration
# Generated on $(date)

SSH_HOST=$server_host
SSH_USERNAME=$ssh_user
SSH_PORT=$ssh_port
SSH_KEY_PATH=$KEY_PATH

# Test connection:
# ssh -i $KEY_PATH -p $ssh_port ${ssh_user}@${server_host}
EOF

echo -e "${GREEN}✅ Configuration saved to $CONFIG_FILE${NC}"
echo ""
