# PowerShell script to download images and insert into database

Write-Host "=== Hero Slider Images Setup ===" -ForegroundColor Cyan
Write-Host ""

# Step 1: Download images
Write-Host "Step 1: Downloading Images" -ForegroundColor Yellow
Write-Host ""

$images = @(
    @{
        url = "https://ichhedanaexpeditions.com/usercontent/794686787.jpg"
        filename = "794686787.jpg"
    },
    @{
        url = "https://ichhedanaexpeditions.com/usercontent/623395350.png"
        filename = "623395350.png"
    }
)

$targetDir = "C:\xampp\htdocs\ichhedanaexpeditions\uploads\gallery"

foreach ($img in $images) {
    $targetPath = Join-Path $targetDir $img.filename
    
    if (Test-Path $targetPath) {
        Write-Host "✓ File already exists: $($img.filename)" -ForegroundColor Green
    } else {
        Write-Host "Downloading $($img.filename)..." -ForegroundColor White
        try {
            Invoke-WebRequest -Uri $img.url -OutFile $targetPath -UseBasicParsing
            Write-Host "✓ Downloaded: $($img.filename)" -ForegroundColor Green
        } catch {
            Write-Host "✗ Failed to download: $($img.filename)" -ForegroundColor Red
            Write-Host "  Error: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

Write-Host ""
Write-Host "Step 2: Inserting into Database" -ForegroundColor Yellow
Write-Host ""

# Use MySQL command line
$mysqlPath = "C:\xampp\mysql\bin\mysql.exe"

if (Test-Path $mysqlPath) {
    $sql = "INSERT IGNORE INTO gallery (title, image_path, category, location, featured, status, display_order, created_at) VALUES ('Wildlife Photography Expedition', '794686787.jpg', 'Wildlife', 'Nepal', 1, 'published', 1, NOW()), ('Mountain Wilderness Adventure', '623395350.png', 'Landscape', 'Himalayas', 1, 'published', 2, NOW());"
    
    $sql | & $mysqlPath -u root ichhedana_expeditions
    
    Write-Host "✓ Images inserted into database!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Checking featured images..." -ForegroundColor White
    
    $checkSql = "SELECT id, title, image_path FROM gallery WHERE featured = 1;"
    $checkSql | & $mysqlPath -u root ichhedana_expeditions -t
} else {
    Write-Host "MySQL not found. Please run insert-images.sql in phpMyAdmin" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== Setup Complete ===" -ForegroundColor Cyan
Write-Host "Visit: http://localhost/ichhedanaexpeditions" -ForegroundColor Green
Write-Host ""
