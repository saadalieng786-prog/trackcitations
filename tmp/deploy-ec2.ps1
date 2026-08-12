# Deploy Track Citations to EC2 (same flow as prior successful deploys)
# Run in PowerShell:  powershell -ExecutionPolicy Bypass -File C:\MAMP\htdocs\trackcitations\tmp\deploy-ec2.ps1

$ErrorActionPreference = "Stop"
$key = "C:\Users\DELL\Downloads\test2peety.pem"
if (-not (Test-Path $key)) { $key = "C:\MAMP\htdocs\trackcitations\tmp\test2peety.pem" }
$remote = "ubuntu@ec2-3-144-24-24.us-east-2.compute.amazonaws.com"
$src = "C:\MAMP\htdocs\trackcitations"
$stamp = Get-Date -Format "yyyyMMddHHmmss"
$release = "trackcitations-$stamp"
$tarball = "$env:TEMP\$release.tgz"

Write-Host "Packaging local code as $release ..."
Push-Location $src
& tar.exe -czf $tarball `
  --exclude=vendor `
  --exclude=node_modules `
  --exclude=.git `
  --exclude=.env `
  --exclude=tmp `
  --exclude=storage `
  --exclude=.cursor `
  --exclude=tests `
  app bootstrap brain config database public resources routes sfdc_datasync `
  artisan composer.json composer.lock package.json package-lock.json `
  vite.config.js tailwind.config.js postcss.config.js `
  .htaccess .gitignore .editorconfig .gitattributes .env.example `
  README.md
Pop-Location
Write-Host "tar size=$((Get-Item $tarball).Length)"

Write-Host "Uploading..."
& scp.exe -i $key -o BatchMode=yes -o IdentitiesOnly=yes $tarball "${remote}:/tmp/$release.tgz"
if ($LASTEXITCODE -ne 0) { throw "scp failed" }

$remoteScript = @"
set -euo pipefail
RELEASE='$release'
CURRENT=`$(readlink -f /var/www/drivertickets)
NEW=/var/www/releases/`$RELEASE

echo "Current: `$CURRENT"
echo "New: `$NEW"

sudo mkdir -p "`$NEW"
sudo tar -xzf /tmp/`$RELEASE.tgz -C "`$NEW"
rm -f /tmp/`$RELEASE.tgz

sudo cp "`$CURRENT/.env" "`$NEW/.env"

sudo rm -rf "`$NEW/storage"
sudo ln -sfn /var/www/drivertickets-shared/storage "`$NEW/storage"
sudo mkdir -p "`$NEW/public"
sudo ln -sfn /var/www/drivertickets-shared/storage/app/public "`$NEW/public/storage"

if [ -d "`$CURRENT/vendor" ]; then
  sudo cp -a "`$CURRENT/vendor" "`$NEW/vendor"
fi

if [ ! -f "`$NEW/public/build/manifest.json" ] && [ -f "`$CURRENT/public/build/manifest.json" ]; then
  sudo mkdir -p "`$NEW/public/build"
  sudo cp -a "`$CURRENT/public/build/." "`$NEW/public/build/"
fi

sudo chown -R www-data:www-data "`$NEW"
sudo chmod -R ug+rwX "`$NEW"

cd "`$NEW"
sudo -u www-data php artisan down --retry=60 || true

if command -v composer >/dev/null 2>&1; then
  sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction --working-dir="`$NEW" 2>&1 | tail -20 || \
  sudo composer install --no-dev --optimize-autoloader --no-interaction --working-dir="`$NEW" 2>&1 | tail -20 || true
else
  echo 'composer not found; keeping copied vendor'
fi

sudo -u www-data php artisan optimize:clear || true
sudo -u www-data php artisan config:cache || true
sudo -u www-data php artisan view:cache || true
sudo -u www-data php artisan event:cache || true

sudo ln -sfn "`$NEW" /var/www/drivertickets

sudo systemctl reload php8.5-fpm 2>/dev/null || sudo systemctl reload php*-fpm 2>/dev/null || true
sudo nginx -t && sudo systemctl reload nginx || true

cd /var/www/drivertickets
sudo -u www-data php artisan up || true

echo '=== VERIFY ==='
readlink -f /var/www/drivertickets
test -f /var/www/drivertickets/app/Integrations/Salesforce/SalesforceSyncLogger.php && echo SYNC_LOGGER_OK
test -f /var/www/drivertickets/resources/views/admin/salesforce/sync-log.blade.php && echo SYNC_LOG_VIEW_OK
grep -q "salesforce.sync" /var/www/drivertickets/routes/web.php && echo SYNC_ROUTE_OK
grep -q "Run Sync Now" /var/www/drivertickets/resources/views/admin/salesforce/index.blade.php && echo SYNC_BUTTON_OK
test -f /var/www/drivertickets/artisan && echo ARTISAN_OK
test -f /var/www/drivertickets/.env && echo ENV_OK
test -L /var/www/drivertickets/storage && echo STORAGE_LINK_OK
php /var/www/drivertickets/artisan --version
curl -sI -o /dev/null -w 'HTTP %{http_code}\n' http://127.0.0.1/ -H 'Host: dev.trackcitations.com' || true
echo DEPLOY_SUCCESS
"@

$remoteScript | & ssh.exe -i $key -o BatchMode=yes -o IdentitiesOnly=yes $remote "bash -s"
$sshExit = $LASTEXITCODE
Remove-Item $tarball -Force -ErrorAction SilentlyContinue
Write-Host "ssh exit=$sshExit"
if ($sshExit -ne 0) { throw "remote deploy failed" }
Write-Host "Done."
