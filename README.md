# Media Action - Max Size

A Joomla media-action plugin that automatically resizes uploaded images to a maximum dimension on the longest side while maintaining aspect ratio.

## Features

- 🖼️ **Automatic Resizing**: Resizes images on upload without user intervention
- 📐 **Maintains Aspect Ratio**: Preserves the original proportions of your images
- ⚙️ **Configurable**: Set your own maximum dimension (default: 3000px)
- 🎯 **Smart Scaling**: Only resizes images that exceed the maximum dimension
- 🔧 **Format Support**: Works with JPG, PNG, GIF, WebP, and AVIF formats
- 🌍 **Multilingual**: Includes English and Dutch translations

## Requirements

- Joomla 4.x, 5.x, or 6.x
- PHP 8.1 or higher

## Installation

### Method 1: Install from GitHub Release

1. Download the latest release ZIP from [Releases](https://github.com/hans2103/plg_media-action_maxsize/releases)
2. In Joomla admin: **System → Install → Extensions**
3. Upload the downloaded ZIP file
4. Enable the plugin: **System → Plugins → Media Action - Max Size**

### Method 2: Install via Update Server

The plugin includes an update server, so after initial installation, updates will be available through Joomla's update system.

## Configuration

1. Go to **System → Plugins**
2. Search for "Max Size"
3. Click on **Media Action - Max Size**
4. Set **Status** to **Published**
5. Configure **Maximum Dimension** (in pixels)
   - Default: 3000px
   - Set to 0 to disable automatic resizing
6. Click **Save & Close**

## Usage

Once enabled, the plugin works automatically:

1. Go to **Content → Media**
2. Upload an image larger than the configured maximum dimension
3. The plugin automatically resizes it so the longest side equals your maximum dimension
4. The aspect ratio is preserved

### Example

If you upload a 5000×3000px image with max dimension set to 3000:
- The image will be resized to **3000×1800px** (landscape)

If you upload a 3000×5000px image:
- The image will be resized to **1800×3000px** (portrait)

## How It Works

The plugin uses Joomla's `onContentBeforeSave` event to intercept image uploads in the Media Manager:

1. Detects if the uploaded file is an image
2. Checks if either dimension exceeds the maximum
3. Calculates new dimensions maintaining aspect ratio
4. Resizes using Joomla's Image class
5. Saves the resized version

## Development & Updates

### Creating a New Release

This repository uses GitHub Actions to automatically create release packages:

1. Update the version in `maxsize.xml` if needed
2. Commit your changes
3. Create and push a new tag:
   ```bash
   git tag v1.0.1
   git push origin v1.0.1
   ```
4. GitHub Actions will automatically:
   - Create a ZIP package
   - Create a GitHub release
   - Update the `update.xml` file
   - Make the update available to Joomla sites

### Update Server

The plugin uses GitHub as an update server:
- **Update XML**: `https://raw.githubusercontent.com/hans2103/plg_media-action_maxsize/main/update.xml`
- Updates are automatically delivered to installed instances through Joomla's update system

## Technical Details

### File Structure

```
plg_media-action_maxsize/
├── .github/
│   └── workflows/
│       └── release.yml          # GitHub Actions workflow
├── language/
│   ├── en-GB/                   # English translations
│   └── nl-NL/                   # Dutch translations
├── services/
│   └── provider.php             # Dependency injection
├── src/
│   └── Extension/
│       └── Maxsize.php          # Main plugin logic
├── maxsize.xml                  # Plugin manifest
├── update.xml                   # Update server XML
└── README.md                    # This file
```

### Events

- `onContentBeforeSave`: Triggered when files are saved through Media Manager

### Supported Image Formats

- JPEG/JPG
- PNG
- GIF
- WebP
- AVIF (if PHP has `imageavif` support)

## License

GNU General Public License version 3

## Author

**HKweb**
- Website: [https://hkweb.nl](https://hkweb.nl)
- Email: info@hkweb.nl

## Support

For bug reports and feature requests, please use the [GitHub issue tracker](https://github.com/hans2103/plg_media-action_maxsize/issues).

## Changelog

### Version 1.0.0 (November 2025)
- Initial release
- Automatic image resizing on upload
- Configurable maximum dimension
- Support for JPG, PNG, GIF, WebP, and AVIF
- English and Dutch translations
- GitHub-based update server
