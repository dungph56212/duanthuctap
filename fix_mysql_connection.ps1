# Fix MySQL Connection Issue - Laravel Project
# This script will help diagnose and fix MySQL connection problems

Write-Host "=== MySQL Connection Diagnostic & Fix ===" -ForegroundColor Green
Write-Host ""

# Step 1: Check if Laragon is running
Write-Host "Step 1: Checking Laragon Services..." -ForegroundColor Yellow
Write-Host "Please ensure Laragon is running with Apache and MySQL services started." -ForegroundColor Cyan
Write-Host "If Laragon is not running, please start it first." -ForegroundColor Red
Write-Host ""

# Step 2: Test MySQL connection
Write-Host "Step 2: Testing MySQL Connection..." -ForegroundColor Yellow
try {
    $mysqlTest = mysql -u root -h 127.0.0.1 -P 3306 -e "SELECT 1;" 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ MySQL connection successful!" -ForegroundColor Green
    } else {
        Write-Host "❌ MySQL connection failed!" -ForegroundColor Red
        Write-Host "Error: $mysqlTest" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ MySQL command not found or connection failed!" -ForegroundColor Red
}
Write-Host ""

# Step 3: Check if database exists
Write-Host "Step 3: Checking if database 'project' exists..." -ForegroundColor Yellow
try {
    $dbCheck = mysql -u root -h 127.0.0.1 -P 3306 -e "SHOW DATABASES LIKE 'project';" 2>&1
    if ($dbCheck -like "*project*") {
        Write-Host "✅ Database 'project' exists!" -ForegroundColor Green
    } else {
        Write-Host "❌ Database 'project' does not exist!" -ForegroundColor Red
        Write-Host "Creating database 'project'..." -ForegroundColor Yellow
        mysql -u root -h 127.0.0.1 -P 3306 -e "CREATE DATABASE IF NOT EXISTS project;"
        Write-Host "✅ Database 'project' created!" -ForegroundColor Green
    }
} catch {
    Write-Host "❌ Could not check database existence!" -ForegroundColor Red
}
Write-Host ""

# Step 4: Clear Laravel cache
Write-Host "Step 4: Clearing Laravel cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
Write-Host "✅ Laravel cache cleared!" -ForegroundColor Green
Write-Host ""

# Step 5: Test Laravel database connection
Write-Host "Step 5: Testing Laravel database connection..." -ForegroundColor Yellow
try {
    $dbTest = php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully!';" 2>&1
    if ($dbTest -like "*successfully*") {
        Write-Host "✅ Laravel database connection successful!" -ForegroundColor Green
    } else {
        Write-Host "❌ Laravel database connection failed!" -ForegroundColor Red
        Write-Host "Error: $dbTest" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Could not test Laravel database connection!" -ForegroundColor Red
}
Write-Host ""

# Step 6: Check sessions table
Write-Host "Step 6: Checking sessions table..." -ForegroundColor Yellow
try {
    $sessionTest = php artisan tinker --execute="Schema::hasTable('sessions') ? 'Sessions table exists' : 'Sessions table missing';" 2>&1
    if ($sessionTest -like "*exists*") {
        Write-Host "✅ Sessions table exists!" -ForegroundColor Green
    } else {
        Write-Host "❌ Sessions table missing!" -ForegroundColor Red
        Write-Host "Creating sessions table..." -ForegroundColor Yellow
        php artisan session:table
        php artisan migrate
        Write-Host "✅ Sessions table created!" -ForegroundColor Green
    }
} catch {
    Write-Host "❌ Could not check sessions table!" -ForegroundColor Red
}
Write-Host ""

Write-Host "=== Manual Steps if Issues Persist ===" -ForegroundColor Cyan
Write-Host "1. Start Laragon application" -ForegroundColor White
Write-Host "2. Click 'Start All' to start Apache and MySQL" -ForegroundColor White
Write-Host "3. Check if MySQL port 3306 is available" -ForegroundColor White
Write-Host "4. Try changing DB_HOST from 127.0.0.1 to localhost in .env" -ForegroundColor White
Write-Host "5. Restart your Laravel development server" -ForegroundColor White
Write-Host ""

Write-Host "=== Next Steps ===" -ForegroundColor Green
Write-Host "1. Run: php artisan serve" -ForegroundColor White
Write-Host "2. Visit: http://localhost:8000" -ForegroundColor White
Write-Host "3. Test the chatbot functionality" -ForegroundColor White
