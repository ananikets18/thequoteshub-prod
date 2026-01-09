# The Quotes Hub - Deployment Files

This directory contains configuration files for deploying to DigitalOcean App Platform.

## Files

- **`app.yaml`** - Main DigitalOcean App Platform configuration
  - Defines web service, database, environment variables
  - Used automatically by DigitalOcean
  - **ACTION REQUIRED:** Update GitHub repo name (lines 28-29)

- **`deploy.template.yaml`** - Simplified deployment template
  - Minimal configuration for quick setup
  - Alternative to app.yaml for simpler deployments

## Usage

1. **Before deployment:**
   - Edit `app.yaml`
   - Update line 28-29: `repo: YOUR_USERNAME/YOUR_REPO_NAME`
   - Commit changes to GitHub

2. **DigitalOcean will:**
   - Automatically detect `app.yaml`
   - Use configuration during deployment
   - Set up web service + database

3. **No manual configuration needed** in DigitalOcean UI if `app.yaml` is configured correctly

## Documentation

See [DEPLOYMENT.md](../DEPLOYMENT.md) for complete deployment guide.
