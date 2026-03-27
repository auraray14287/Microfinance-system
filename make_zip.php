<?php
$source = 'C:/xampp/htdocs/loan-management-system';
$output = 'C:/xampp/htdocs/rafikibora.zip';

if (file_exists($output)) unlink($output);

$zip = new ZipArchive();
$zip->open($output, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$skip = ['node_modules', '.git', 'vendor'];

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS)
);

$count = 0;
foreach ($files as $file) {
    if ($file->isDir()) continue;
    $path = str_replace('\\', '/', $file->getRealPath());
    $skip_file = false;
    foreach ($skip as $s) {
        if (strpos($path, '/' . $s . '/') !== false) {
            $skip_file = true;
            break;
        }
    }
    if (!$skip_file) {
        $relative = substr($path, strlen('C:/xampp/htdocs/'));
        $zip->addFile($path, $relative);
        $count++;
    }
}

$zip->close();
echo "Done: " . $count . " files, " . round(filesize($output) / 1024 / 1024, 2) . " MB\n";
