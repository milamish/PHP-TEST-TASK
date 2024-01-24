<?php
/**
13.Write a PHP script that serializes a large data structure (e.g., an array or object), compresses
it, saves it to a file, and then unserializes and decompresses the data from the file. You can
use standard PHP functions for serialization and a compression library like zlib to achieve
this.
*/

// Read the large data from a config file
$configFilePath = 'config.json';

if (file_exists($configFilePath)) {
    $largeData = include $configFilePath;
} else {
    throw new \Exception("Config file $configFilePath not found");
}

// Serializing and compressing the data
$serializedData = serialize($largeData);
$compressedData = gzcompress($serializedData, 9); // 9 is the compression level (0-9, where 9 is maximum compression)

// Saving the compressed data to a file
$filePath = 'serialized-data.dat';

if (file_put_contents($filePath, $compressedData) === false) {
    die("Error saving compressed data to $filePath");
}


// Reading the compressed data from the file
$readCompressedData = file_get_contents($filePath);

if ($readCompressedData === false) {
    die("Error reading compressed data from $filePath");
}

// Decompressing the data
$uncompressedData = gzuncompress($readCompressedData);

if ($uncompressedData === false) {
    die("Error decompressing data from $filePath");
}

// Unserializing the data
$unserializedData = unserialize($uncompressedData);

if ($unserializedData === false) {
    die("Error unserializing data from $filePath");
}

// Display the unserialized data
print_r($unserializedData);
echo " process successful";

?>



