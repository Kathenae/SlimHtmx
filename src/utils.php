<?php 

// Function to color the text
function color($text, $color)
{
    // Map color names to their corresponding codes
    $colorCodes = [
        'red' => "\033[31m",
        'green' => "\033[32m",
        'blue' => "\033[34m",
        'yellow' => "\033[33m",
        'cyan' => "\033[36m",
        'magenta' => "\033[35m",
        'white' => "\033[37m",
    ];

    // Check if the color is supported
    if (!isset ($colorCodes[$color])) {
        return "";
    }

    // Output the text with the color
    return ($colorCodes[$color] . $text . "\033[0m");
}

function println($text)
{
    echo $text . PHP_EOL;
}