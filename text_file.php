<?php

// Function to count words in a text file
function countWordsInFile($filename) {
    // opens the file
    $fileHandle = fopen($filename, 'r');

    // Check if the file is successfully opened
    if ($fileHandle === false) {
        die("Error: Unable to open file $filename");
    }

    $wordCount = 0;

    // Reads file and counts words
    while (!feof($fileHandle)) {
        $line = fgets($fileHandle);
        $wordCount += str_word_count($line);
    }

    fclose($fileHandle);

    return $wordCount;
}

//path to your text file
$filePath = 'sample.txt';

try {
    $result = countWordsInFile($filePath);
    echo "Number of words in the file: $result";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>

