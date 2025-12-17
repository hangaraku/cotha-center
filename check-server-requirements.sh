#!/bin/bash

# Server Requirements Check Script
# Run this on your production server to verify it meets all requirements

set -e

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Production Server Requirements Check${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

ERRORS=0
WARNINGS=0

# Function to check requirement
check_requirement() {
    local name=$1
    local command=$2
    local required_version=$3
    local current_version
    
    echo -n "Checking $name... "
    
    if ! command -v $command &> /dev/null; then
        echo -e "${RED}✗ NOT FOUND${NC}"
        ERRORS=$((ERRORS+1))
        return 1
    fi
    
    current_version=$($command --version 2>&1 | head -n1)
    echo -e "${GREEN}✓ FOUND${NC}"
    echo "  Version: $current_version"
    
    if [ ! -z "$required_version" ]; then
        echo "  Required: $required_version"
    fi
    
    return 0
}

# Check PHP
echo -e "${YELLOW}1. Checking PHP...${NC}"
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
    PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")
    
    echo -e "   ${GREEN}✓${NC} PHP found"
    echo "   Version: $PHP_VERSION"
    
    if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 3 ]); then
        echo -e "   ${RED}✗ PHP 8.3 or higher required!${NC}"
        echo "   Current version: $PHP_VERSION"
        ERRORS=$((ERRORS+1))
    else
        echo -e "   ${GREEN}✓ PHP version meets requirements${NC}"
    fi
else
    echo -e "   ${RED}✗ PHP not found!${NC}"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check PHP extensions
echo -e "${YELLOW}2. Checking PHP extensions...${NC}"
required_extensions=("mbstring" "xml" "ctype" "json" "pdo" "mysql" "curl" "gd" "zip" "bcmath")

for ext in "${required_extensions[@]}"; do
    echo -n "   Checking $ext... "
    if php -m | grep -q "^$ext$"; then
        echo -e "${GREEN}✓${NC}"
    else
        echo -e "${RED}✗ MISSING${NC}"
        WARNINGS=$((WARNINGS+1))
    fi
done
echo ""

# Check Composer
echo -e "${YELLOW}3. Checking Composer...${NC}"
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version 2>&1 | head -n1)
    echo -e "   ${GREEN}✓${NC} Composer found"
    echo "   Version: $COMPOSER_VERSION"
else
    echo -e "   ${RED}✗ Composer not found!${NC}"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check Node.js
echo -e "${YELLOW}4. Checking Node.js...${NC}"
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    echo -e "   ${GREEN}✓${NC} Node.js found"
    echo "   Version: $NODE_VERSION"
    
    # Check if Node version is >= 18
    NODE_MAJOR=$(node -e "console.log(process.versions.node.split('.')[0])")
    if [ "$NODE_MAJOR" -lt 18 ]; then
        echo -e "   ${YELLOW}⚠${NC} Node.js 18+ recommended (current: $NODE_VERSION)"
        WARNINGS=$((WARNINGS+1))
    fi
else
    echo -e "   ${RED}✗ Node.js not found!${NC}"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check npm
echo -e "${YELLOW}5. Checking npm...${NC}"
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm --version)
    echo -e "   ${GREEN}✓${NC} npm found"
    echo "   Version: $NPM_VERSION"
else
    echo -e "   ${RED}✗ npm not found!${NC}"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check Git
echo -e "${YELLOW}6. Checking Git...${NC}"
if command -v git &> /dev/null; then
    GIT_VERSION=$(git --version)
    echo -e "   ${GREEN}✓${NC} Git found"
    echo "   Version: $GIT_VERSION"
else
    echo -e "   ${RED}✗ Git not found!${NC}"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check project directory
echo -e "${YELLOW}7. Checking project directory...${NC}"
PROJECT_DIR="/home/comfypace/htdocs/comfypace.com"
if [ -d "$PROJECT_DIR" ]; then
    echo -e "   ${GREEN}✓${NC} Project directory exists"
    echo "   Path: $PROJECT_DIR"
    
    # Check if it's a git repository
    if [ -d "$PROJECT_DIR/.git" ]; then
        echo -e "   ${GREEN}✓${NC} Git repository initialized"
    else
        echo -e "   ${YELLOW}⚠${NC} Not a git repository"
        WARNINGS=$((WARNINGS+1))
    fi
    
    # Check permissions
    if [ -w "$PROJECT_DIR" ]; then
        echo -e "   ${GREEN}✓${NC} Directory is writable"
    else
        echo -e "   ${RED}✗ Directory is not writable!${NC}"
        ERRORS=$((ERRORS+1))
    fi
else
    echo -e "   ${RED}✗ Project directory not found!${NC}"
    echo "   Expected: $PROJECT_DIR"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check storage permissions
echo -e "${YELLOW}8. Checking storage permissions...${NC}"
if [ -d "$PROJECT_DIR/storage" ]; then
    if [ -w "$PROJECT_DIR/storage" ]; then
        echo -e "   ${GREEN}✓${NC} storage/ is writable"
    else
        echo -e "   ${RED}✗ storage/ is not writable!${NC}"
        ERRORS=$((ERRORS+1))
    fi
else
    echo -e "   ${YELLOW}⚠${NC} storage/ directory not found"
    WARNINGS=$((WARNINGS+1))
fi

if [ -d "$PROJECT_DIR/bootstrap/cache" ]; then
    if [ -w "$PROJECT_DIR/bootstrap/cache" ]; then
        echo -e "   ${GREEN}✓${NC} bootstrap/cache/ is writable"
    else
        echo -e "   ${RED}✗ bootstrap/cache/ is not writable!${NC}"
        ERRORS=$((ERRORS+1))
    fi
else
    echo -e "   ${YELLOW}⚠${NC} bootstrap/cache/ directory not found"
    WARNINGS=$((WARNINGS+1))
fi
echo ""

# Check .env file
echo -e "${YELLOW}9. Checking environment configuration...${NC}"
if [ -f "$PROJECT_DIR/.env" ]; then
    echo -e "   ${GREEN}✓${NC} .env file exists"
else
    echo -e "   ${RED}✗ .env file not found!${NC}"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Summary
echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Summary${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}✓ All checks passed! Your server is ready for deployment.${NC}"
    exit 0
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠ $WARNINGS warning(s) found. Deployment may work but review warnings above.${NC}"
    exit 0
else
    echo -e "${RED}✗ $ERRORS error(s) found. Please fix them before deploying.${NC}"
    if [ $WARNINGS -gt 0 ]; then
        echo -e "${YELLOW}⚠ $WARNINGS warning(s) also found.${NC}"
    fi
    echo ""
    echo "Common fixes:"
    echo "  - Upgrade PHP: See PHP_VERSION_FIX.md"
    echo "  - Install Composer: curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"
    echo "  - Install Node.js: curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs"
    echo "  - Fix permissions: chmod -R 755 storage bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache"
    exit 1
fi
