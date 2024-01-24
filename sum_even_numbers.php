<?php
function sumOfEvenNumbers($numbers) {
    $sum = 0;

    foreach ($numbers as $number) {
        if ($number % 2 === 0) {
            $sum += $number;
        }
    }

    return $sum;
}

$myArray = [12, 2, 30, 4, 5, 62, 7, 8, 9, 10, 20];
$result = sumOfEvenNumbers($myArray);
echo "Sum of even numbers: $result";
?>

