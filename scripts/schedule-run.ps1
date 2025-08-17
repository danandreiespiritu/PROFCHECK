# Runs Laravel's schedule:run once and logs output. Intended to be called by Task Scheduler every minute.
Param(
    [string]$ProjectRoot = "C:\xampp\htdocs\RFIDAttendanceSystem\RFIDAttendanceSystem",
    [string]$PhpPath = "C:\xampp\php\php.exe"
)

if (-not (Test-Path $ProjectRoot)) {
    Write-Error "Project root not found: $ProjectRoot"
    exit 1
}

if (-not (Test-Path $PhpPath)) {
    Write-Warning "Configured PHP executable not found at $PhpPath. Falling back to 'php' in PATH." 
    $PhpPath = "php"
}

$logDir = Join-Path $ProjectRoot 'storage\logs'
if (-not (Test-Path $logDir)) { New-Item -ItemType Directory -Path $logDir -Force | Out-Null }
$logFile = Join-Path $logDir 'scheduler.log'

Push-Location $ProjectRoot
try {
    $ts = Get-Date -Format o
    "$ts Running: $PhpPath artisan app:mark-faculty-absents" | Out-File -FilePath $logFile -Encoding utf8 -Append

    # Execute the specific artisan command and capture output (stdout + stderr)
    $processOutput = & "$PhpPath" artisan app:mark-faculty-absents 2>&1

    if ($processOutput -is [System.Array]) {
        $processOutput | Out-File -FilePath $logFile -Encoding utf8 -Append
    } else {
        "$processOutput" | Out-File -FilePath $logFile -Encoding utf8 -Append
    }

    $tsEnd = Get-Date -Format o
    "$tsEnd Done." | Out-File -FilePath $logFile -Encoding utf8 -Append
} finally {
    Pop-Location
}
