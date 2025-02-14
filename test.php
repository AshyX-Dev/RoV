<?php
$boolTrue = true;
$boolFalse = false;

$intTrueExplicit = (int)$boolTrue;  // $intTrueExplicit will be 1
$intFalseExplicit = (int)$boolFalse; // $intFalseExplicit will be 0

echo $intTrueExplicit;
echo "\n";
echo $intFalseExplicit;