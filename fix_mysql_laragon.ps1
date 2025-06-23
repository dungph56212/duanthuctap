Write-Host "=== Laragon MySQL Connection Fix ===" -ForegroundColor Green
Write-Host ""

# Step 1: Check if Laragon is running
Write-Host "Step 1: Checking Laragon processes..." -ForegroundColor Yellow
$laragonProcesses = Get-Process | Where-Object {$_.ProcessName -like "*laragon*" -or $_.ProcessName -like "*mysql*" -or $_.ProcessName -like "*apache*"}

if ($laragonProcesses) {
    Write-Host "✓ Found running processes:" -ForegroundColor Green
    $laragonProcesses | Format-Table ProcessName, Id, CPU -AutoSize
} else {
    Write-Host "✗ No Laragon processes found running!" -ForegroundColor Red
    Write-Host "Please start Laragon first!" -ForegroundColor Yellow
}

Write-Host ""

# Step 2: Check what's running on common MySQL ports
Write-Host "Step 2: Checking MySQL ports..." -ForegroundColor Yellow
$ports = @(3306, 3307, 3308)

foreach ($port in $ports) {
    try {
        $connection = Test-NetConnection -ComputerName "127.0.0.1" -Port $port -WarningAction SilentlyContinue
        if ($connection.TcpTestSucceeded) {
            Write-Host "✓ Port $port is open and accepting connections" -ForegroundColor Green
            
            # Try to connect to MySQL on this port
            Write-Host "  Testing MySQL connection on port $port..." -ForegroundColor Cyan
            $testCommand = "mysql -h 127.0.0.1 -P $port -u root --password= -e 'SELECT VERSION();' 2>&1"
            $result = Invoke-Expression $testCommand
            
            if ($LASTEXITCODE -eq 0) {
                Write-Host "  ✓ MySQL is working on port $port!" -ForegroundColor Green
                Write-Host "  MySQL Version: $result" -ForegroundColor Cyan
                
                # Update .env file if port is different
                if ($port -ne 3306) {
                    Write-Host "  Updating .env file to use port $port..." -ForegroundColor Yellow
                    $envPath = "c:\laragon\www\project\.env"
                    $envContent = Get-Content $envPath -Raw
                    $envContent = $envContent -replace "DB_PORT=3306", "DB_PORT=$port"
                    Set-Content -Path $envPath -Value $envContent
                    Write-Host "  ✓ Updated DB_PORT to $port in .env file" -ForegroundColor Green
                }
            } else {
                Write-Host "  ✗ Port $port is open but MySQL is not responding properly" -ForegroundColor Red
                Write-Host "  Error: $result" -ForegroundColor Red
            }
        } else {
            Write-Host "✗ Port $port is not accessible" -ForegroundColor Red
        }
    } catch {
        Write-Host "✗ Error checking port $port : $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host ""

# Step 3: Try to start Laragon services
Write-Host "Step 3: Attempting to start Laragon services..." -ForegroundColor Yellow

# Common Laragon installation paths
$laragonPaths = @(
    "C:\laragon\laragon.exe",
    "C:\Program Files\Laragon\laragon.exe",
    "C:\Program Files (x86)\Laragon\laragon.exe"
)

$laragonPath = $null
foreach ($path in $laragonPaths) {
    if (Test-Path $path) {
        $laragonPath = $path
        break
    }
}

if ($laragonPath) {
    Write-Host "✓ Found Laragon at: $laragonPath" -ForegroundColor Green
    Write-Host "Starting Laragon..." -ForegroundColor Yellow
    
    try {
        Start-Process -FilePath $laragonPath -ArgumentList "start" -NoNewWindow -Wait
        Write-Host "✓ Laragon start command executed" -ForegroundColor Green
        
        # Wait a moment for services to start
        Write-Host "Waiting for services to start..." -ForegroundColor Yellow
        Start-Sleep -Seconds 5
        
        # Test MySQL connection again
        Write-Host "Testing MySQL connection after restart..." -ForegroundColor Yellow
        $testResult = & mysql -h 127.0.0.1 -P 3306 -u root --password= -e "SELECT 'Connection successful' as Status;" 2>&1
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✓ MySQL connection successful!" -ForegroundColor Green
        } else {
            Write-Host "✗ MySQL still not connecting: $testResult" -ForegroundColor Red
        }
        
    } catch {
        Write-Host "✗ Error starting Laragon: $($_.Exception.Message)" -ForegroundColor Red
    }
} else {
    Write-Host "✗ Laragon executable not found in common locations" -ForegroundColor Red
    Write-Host "Please start Laragon manually from the Start menu or desktop shortcut" -ForegroundColor Yellow
}

Write-Host ""

# Step 4: Create database if connection works
Write-Host "Step 4: Creating database if needed..." -ForegroundColor Yellow

$createDbCommand = @"
mysql -h 127.0.0.1 -P 3306 -u root --password= -e "CREATE DATABASE IF NOT EXISTS project; SHOW DATABASES LIKE 'project';" 2>&1
"@

$dbResult = Invoke-Expression $createDbCommand

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Database 'project' exists or was created successfully" -ForegroundColor Green
    Write-Host "Database result: $dbResult" -ForegroundColor Cyan
} else {
    Write-Host "✗ Could not create database: $dbResult" -ForegroundColor Red
}

Write-Host ""

# Step 5: Test Laravel connection
Write-Host "Step 5: Testing Laravel database connection..." -ForegroundColor Yellow

$laravelTest = @"
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Laravel DB connection: SUCCESS'; } catch(Exception `$e) { echo 'Laravel DB connection: FAILED - ' . `$e->getMessage(); }"
"@

try {
    $laravelResult = Invoke-Expression $laravelTest
    Write-Host "Laravel connection test: $laravelResult" -ForegroundColor Cyan
} catch {
    Write-Host "✗ Error testing Laravel connection: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== Summary ===" -ForegroundColor Green
Write-Host "1. Make sure Laragon is running (green 'Start All' button should show 'Stop All')" -ForegroundColor White
Write-Host "2. MySQL should be accessible on port 3306 (or updated port in .env)" -ForegroundColor White
Write-Host "3. Database 'project' should exist" -ForegroundColor White
Write-Host "4. Try running your Laravel application again" -ForegroundColor White
Write-Host ""
Write-Host "If issues persist, try:" -ForegroundColor Yellow
Write-Host "- Restart Laragon completely (Stop All → Exit → Restart Laragon)" -ForegroundColor White
Write-Host "- Check Laragon logs for any error messages" -ForegroundColor White
Write-Host "- Verify MySQL service is running in Laragon" -ForegroundColor White
