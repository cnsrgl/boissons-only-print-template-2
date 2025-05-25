<?php
/**
 * Plugin Packaging Script
 * 
 * Run this script to package the plugin as a ZIP file.
 * Usage: php package.php
 */

// Configuration
$plugin_slug = 'boissons-only-print-template';
$plugin_version = '1.0.0';
$output_dir = dirname(__FILE__) . '/../'; // Parent directory
$plugin_dir = dirname(__FILE__);

// Files to include
$files = [
    'CHANGELOG.md',
    'html.php',
    'index.php',
    'LICENSE.txt',
    'plain.php',
    'README-TR.md',
    'readme.txt',
    'style.php',
    'template.php',
    'test-categories.php',
    'package.json',
    'languages/boissons-print-template.pot'
];

// Create ZIP archive
$zip_filename = $output_dir . $plugin_slug . '-' . $plugin_version . '.zip';
$zip = new ZipArchive();

if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    exit("Cannot create ZIP file: $zip_filename\n");
}

// Create plugin directory structure in ZIP
$zip->addEmptyDir($plugin_slug);
$zip->addEmptyDir($plugin_slug . '/languages');

// Add files to the ZIP
foreach ($files as $file) {
    $filepath = $plugin_dir . '/' . $file;
    if (file_exists($filepath)) {
        // Add file to ZIP with proper path
        $zip->addFile($filepath, $plugin_slug . '/' . $file);
        echo "Added: $file\n";
    } else {
        echo "Warning: $file not found\n";
    }
}

$zip->close();
echo "Plugin packaged successfully: $zip_filename\n";
