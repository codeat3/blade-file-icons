<?php

use Codeat3\BladeIconGeneration\Exceptions\InvalidFileExtensionException;
use Codeat3\BladeIconGeneration\IconProcessor;

$svgNormalization = static function (string $tempFilepath, array $iconSet) {

    // perform generic optimizations
    $iconProcessor = new IconProcessor($tempFilepath, $iconSet);
    $iconProcessor
        ->optimize(pre: function (&$svgEL) {
            $width = $svgEL->getAttribute('width');
            $height = $svgEL->getAttribute('height');

            $viewBox = '0 0 '.str_replace('px', '', $width).' '.str_replace('px', '', $height);
            $svgEL->setAttribute('viewBox', $viewBox);
        })
        ->postOptimizationAsString(function ($svgLine){
            // remove style and all the class attributes
            $replacePatterns = [
                '/\s(class=\"[a-z0-9A-Z]+\")/' => '',
                '/\<style.*\>.*\<\/style\>/' => '',
            ];

            $svgLine = preg_replace(array_keys($replacePatterns), array_values($replacePatterns), $svgLine);

            return $svgLine;
        })
        ->save();

};

return [
    [
        // Define a source directory for the sets like a node_modules/ or vendor/ directory...
        'source' => __DIR__.'/../dist/svg/',

        // Define a destination directory for your icons. The below is a good default...
        'destination' => __DIR__.'/../resources/svg',

        // Enable "safe" mode which will prevent deletion of old icons...
        'safe' => true,

        // Call an optional callback to manipulate the icon
        // with the pathname of the icon and the settings from above...
        'after' => $svgNormalization,

        'is-solid' => true,

    ],
];
