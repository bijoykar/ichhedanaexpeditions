# Uploads Directory Structure

This directory contains all user-uploaded files organized by type.

## Directory Structure

```
uploads/
├── tours/              # Tour package images
├── destinations/       # Destination photos
├── blog/              # Blog post featured images
├── gallery/           # Gallery photos
└── reviews/           # Customer profile photos
```

## Permissions

**Linux/Mac:**
```bash
chmod 755 uploads/
chmod 755 uploads/tours/
chmod 755 uploads/destinations/
chmod 755 uploads/blog/
chmod 755 uploads/gallery/
chmod 755 uploads/reviews/
```

**Windows:**
- Right-click on each folder
- Properties → Security
- Give write permissions to the web server user (IUSR or IIS_IUSRS)

## File Types Allowed

- **Images**: JPG, JPEG, PNG, GIF, WEBP
- **Maximum Size**: 5MB per file
- **Automatic Resizing**: Yes (for thumbnails and optimized versions)

## Security

- PHP execution is disabled in uploads directory (.htaccess)
- File type validation on upload
- File size validation
- Unique filenames to prevent overwrites
- Images are validated before processing

## Backup

Remember to include this directory in your backup strategy:
- Include all subdirectories
- Backup regularly (recommended: daily)
- Store backups off-server

## Usage

Files are uploaded through the admin panel:
1. Admin → Tours/Destinations/Blog/Gallery/Reviews
2. Click "Upload Image" or "Add New"
3. Select image file
4. System automatically:
   - Validates file
   - Generates unique filename
   - Resizes if needed
   - Saves to appropriate directory

## Notes

- Keep original images backed up separately
- Optimize images before upload when possible
- Monitor disk space usage
- Clean up unused images periodically
- Consider CDN for production use

---

**Created**: January 8, 2026
