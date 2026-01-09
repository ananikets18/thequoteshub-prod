# ============================================
# PRE-DEPLOYMENT CHECKLIST (PowerShell)
# ============================================
# Run this before pushing to production
# Usage: .\pre-deploy-check.ps1

Write-Host "🔍 Pre-Deployment Checklist for The Quotes Hub" -ForegroundColor Cyan
Write-Host "==============================================" -ForegroundColor Cyan
Write-Host ""

$Errors = 0

# Check 1: .env file not committed
Write-Host "✓ Checking .env is not tracked..." -ForegroundColor Yellow
try {
    git ls-files --error-unmatch config/.env 2>$null
    Write-Host "❌ ERROR: config/.env is tracked by Git!" -ForegroundColor Red
    Write-Host "   Run: git rm --cached config/.env" -ForegroundColor Red
    $Errors++
} catch {
    Write-Host "✅ .env is not tracked" -ForegroundColor Green
}

# Check 2: .gitignore exists
Write-Host ""
Write-Host "✓ Checking .gitignore..." -ForegroundColor Yellow
if (Test-Path ".gitignore") {
    Write-Host "✅ .gitignore exists" -ForegroundColor Green
} else {
    Write-Host "❌ ERROR: .gitignore not found!" -ForegroundColor Red
    $Errors++
}

# Check 3: .do/app.yaml configured
Write-Host ""
Write-Host "✓ Checking .do/app.yaml..." -ForegroundColor Yellow
if (Test-Path ".do/app.yaml") {
    $content = Get-Content ".do/app.yaml" -Raw
    if ($content -match "YOUR_GITHUB_USERNAME/YOUR_REPO_NAME") {
        Write-Host "⚠️  WARNING: Update GitHub repo in .do/app.yaml" -ForegroundColor Yellow
        Write-Host "   Line 28-29: repo: YOUR_GITHUB_USERNAME/YOUR_REPO_NAME" -ForegroundColor Yellow
        $Errors++
    } else {
        Write-Host "✅ .do/app.yaml configured" -ForegroundColor Green
    }
} else {
    Write-Host "❌ ERROR: .do/app.yaml not found!" -ForegroundColor Red
    $Errors++
}

# Check 4: composer.json exists
Write-Host ""
Write-Host "✓ Checking composer.json..." -ForegroundColor Yellow
if (Test-Path "composer.json") {
    Write-Host "✅ composer.json exists" -ForegroundColor Green
} else {
    Write-Host "❌ ERROR: composer.json not found!" -ForegroundColor Red
    $Errors++
}

# Check 5: index.php exists
Write-Host ""
Write-Host "✓ Checking index.php..." -ForegroundColor Yellow
if (Test-Path "index.php") {
    Write-Host "✅ index.php exists" -ForegroundColor Green
} else {
    Write-Host "❌ ERROR: index.php not found!" -ForegroundColor Red
    $Errors++
}

# Check 6: config/env.php exists
Write-Host ""
Write-Host "✓ Checking config/env.php..." -ForegroundColor Yellow
if (Test-Path "config/env.php") {
    Write-Host "✅ config/env.php exists" -ForegroundColor Green
} else {
    Write-Host "❌ ERROR: config/env.php not found!" -ForegroundColor Red
    $Errors++
}

# Check 7: PHP syntax check (requires PHP in PATH)
Write-Host ""
Write-Host "✓ Checking PHP syntax..." -ForegroundColor Yellow
try {
    $phpFiles = Get-ChildItem -Path . -Filter "*.php" -Recurse | Where-Object { $_.FullName -notmatch "vendor" }
    $syntaxErrors = $false
    foreach ($file in $phpFiles) {
        $result = php -l $file.FullName 2>&1
        if ($result -match "error") {
            Write-Host "❌ Syntax error in: $($file.Name)" -ForegroundColor Red
            $syntaxErrors = $true
        }
    }
    if (-not $syntaxErrors) {
        Write-Host "✅ No PHP syntax errors" -ForegroundColor Green
    } else {
        $Errors++
    }
} catch {
    Write-Host "⚠️  WARNING: PHP not found in PATH, skipping syntax check" -ForegroundColor Yellow
}

# Check 8: Git status clean
Write-Host ""
Write-Host "✓ Checking Git status..." -ForegroundColor Yellow
$gitStatus = git status --short
if ([string]::IsNullOrWhiteSpace($gitStatus)) {
    Write-Host "✅ All changes committed" -ForegroundColor Green
} else {
    Write-Host "⚠️  WARNING: Uncommitted changes detected" -ForegroundColor Yellow
    git status --short
}

# Check 9: Current branch
Write-Host ""
Write-Host "✓ Checking Git branch..." -ForegroundColor Yellow
$currentBranch = git branch --show-current
if ($currentBranch -eq "main" -or $currentBranch -eq "master") {
    Write-Host "✅ On main/master branch" -ForegroundColor Green
} else {
    Write-Host "⚠️  WARNING: Not on main/master branch (current: $currentBranch)" -ForegroundColor Yellow
}

# Check 10: Remote configured
Write-Host ""
Write-Host "✓ Checking Git remote..." -ForegroundColor Yellow
try {
    $remoteUrl = git remote get-url origin 2>$null
    Write-Host "✅ Remote configured: $remoteUrl" -ForegroundColor Green
} catch {
    Write-Host "❌ ERROR: No Git remote configured!" -ForegroundColor Red
    Write-Host "   Run: git remote add origin https://github.com/USERNAME/REPO.git" -ForegroundColor Red
    $Errors++
}

# Summary
Write-Host ""
Write-Host "==============================================" -ForegroundColor Cyan
if ($Errors -eq 0) {
    Write-Host "✅ All checks passed! Ready for deployment." -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:" -ForegroundColor Cyan
    Write-Host "1. git push origin main" -ForegroundColor White
    Write-Host "2. Create DigitalOcean App (if not done)" -ForegroundColor White
    Write-Host "3. Monitor deployment in DO dashboard" -ForegroundColor White
} else {
    Write-Host "❌ $Errors error(s) found. Fix before deploying!" -ForegroundColor Red
    exit 1
}
