#!/bin/bash

# ============================================
# PRE-DEPLOYMENT CHECKLIST SCRIPT
# ============================================
# Run this before pushing to production
# Usage: bash pre-deploy-check.sh

echo "🔍 Pre-Deployment Checklist for The Quotes Hub"
echo "=============================================="
echo ""

ERRORS=0

# Check 1: .env file not committed
echo "✓ Checking .env is not tracked..."
if git ls-files --error-unmatch config/.env 2>/dev/null; then
    echo "❌ ERROR: config/.env is tracked by Git!"
    echo "   Run: git rm --cached config/.env"
    ERRORS=$((ERRORS + 1))
else
    echo "✅ .env is not tracked"
fi

# Check 2: .gitignore exists
echo ""
echo "✓ Checking .gitignore..."
if [ -f ".gitignore" ]; then
    echo "✅ .gitignore exists"
else
    echo "❌ ERROR: .gitignore not found!"
    ERRORS=$((ERRORS + 1))
fi

# Check 3: .do/app.yaml configured
echo ""
echo "✓ Checking .do/app.yaml..."
if [ -f ".do/app.yaml" ]; then
    if grep -q "YOUR_GITHUB_USERNAME/YOUR_REPO_NAME" .do/app.yaml; then
        echo "⚠️  WARNING: Update GitHub repo in .do/app.yaml"
        echo "   Line 28-29: repo: YOUR_GITHUB_USERNAME/YOUR_REPO_NAME"
        ERRORS=$((ERRORS + 1))
    else
        echo "✅ .do/app.yaml configured"
    fi
else
    echo "❌ ERROR: .do/app.yaml not found!"
    ERRORS=$((ERRORS + 1))
fi

# Check 4: composer.json exists
echo ""
echo "✓ Checking composer.json..."
if [ -f "composer.json" ]; then
    echo "✅ composer.json exists"
else
    echo "❌ ERROR: composer.json not found!"
    ERRORS=$((ERRORS + 1))
fi

# Check 5: index.php exists
echo ""
echo "✓ Checking index.php..."
if [ -f "index.php" ]; then
    echo "✅ index.php exists"
else
    echo "❌ ERROR: index.php not found!"
    ERRORS=$((ERRORS + 1))
fi

# Check 6: config/env.php exists
echo ""
echo "✓ Checking config/env.php..."
if [ -f "config/env.php" ]; then
    echo "✅ config/env.php exists"
else
    echo "❌ ERROR: config/env.php not found!"
    ERRORS=$((ERRORS + 1))
fi

# Check 7: PHP syntax check
echo ""
echo "✓ Checking PHP syntax..."
SYNTAX_ERRORS=$(find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \; 2>&1 | grep -i "error" || true)
if [ -z "$SYNTAX_ERRORS" ]; then
    echo "✅ No PHP syntax errors"
else
    echo "❌ PHP syntax errors found:"
    echo "$SYNTAX_ERRORS"
    ERRORS=$((ERRORS + 1))
fi

# Check 8: Git status clean
echo ""
echo "✓ Checking Git status..."
if git diff-index --quiet HEAD --; then
    echo "✅ All changes committed"
else
    echo "⚠️  WARNING: Uncommitted changes detected"
    git status --short
fi

# Check 9: Current branch
echo ""
echo "✓ Checking Git branch..."
CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" = "main" ] || [ "$CURRENT_BRANCH" = "master" ]; then
    echo "✅ On main/master branch"
else
    echo "⚠️  WARNING: Not on main/master branch (current: $CURRENT_BRANCH)"
fi

# Check 10: Remote configured
echo ""
echo "✓ Checking Git remote..."
if git remote get-url origin >/dev/null 2>&1; then
    REMOTE_URL=$(git remote get-url origin)
    echo "✅ Remote configured: $REMOTE_URL"
else
    echo "❌ ERROR: No Git remote configured!"
    echo "   Run: git remote add origin https://github.com/USERNAME/REPO.git"
    ERRORS=$((ERRORS + 1))
fi

# Summary
echo ""
echo "=============================================="
if [ $ERRORS -eq 0 ]; then
    echo "✅ All checks passed! Ready for deployment."
    echo ""
    echo "Next steps:"
    echo "1. git push origin main"
    echo "2. Create DigitalOcean App (if not done)"
    echo "3. Monitor deployment in DO dashboard"
else
    echo "❌ $ERRORS error(s) found. Fix before deploying!"
    exit 1
fi
