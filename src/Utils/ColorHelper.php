<?php

namespace App\Utils;

class ColorHelper
{
    public static $baseColors = [
        ['r' => 31,  'g' => 119, 'b' => 180],
        ['r' => 255, 'g' => 127, 'b' => 14],
        ['r' => 44,  'g' => 162, 'b' => 95],
        ['r' => 214, 'g' => 39,  'b' => 40],
        ['r' => 148, 'g' => 103, 'b' => 189],
        ['r' => 140, 'g' => 86,  'b' => 75],
        ['r' => 227, 'g' => 119, 'b' => 194],
        ['r' => 127, 'g' => 127, 'b' => 127],
        ['r' => 188, 'g' => 189, 'b' => 34],
        ['r' => 23,  'g' => 190, 'b' => 207],
        ['r' => 174, 'g' => 199, 'b' => 232],
        ['r' => 255, 'g' => 187, 'b' => 120],
        ['r' => 152, 'g' => 223, 'b' => 138],
        ['r' => 255, 'g' => 152, 'b' => 150],
        ['r' => 197, 'g' => 176, 'b' => 213],
        ['r' => 196, 'g' => 156, 'b' => 148],
        ['r' => 247, 'g' => 182, 'b' => 210],
        ['r' => 199, 'g' => 199, 'b' => 199],
        ['r' => 219, 'g' => 219, 'b' => 141],
        ['r' => 158, 'g' => 218, 'b' => 229],
    ];

    protected static $assignedColors = [];
    // Track used color indices
    protected static $usedIndices = [];

    public static function generateColorFromString(string $input, float $alpha = 0.6): string
    {
        if (isset(self::$assignedColors[$input])) {
            $colorIndex = self::$assignedColors[$input];
        } else {
            $hash = md5($input);
            $proposedIndex = hexdec(substr($hash, 0, 8)) % count(self::$baseColors);

            // If taken, try to find next available color index
            $colorIndex = $proposedIndex;
            for ($i = 0, $iMax = count(self::$baseColors); $i < $iMax; $i++) {
                $tryIndex = ($proposedIndex + $i) % count(self::$baseColors);
                if (!in_array($tryIndex, self::$usedIndices, true)) {
                    $colorIndex = $tryIndex;
                    break;
                }
            }

            // Register assignment
            self::$assignedColors[$input] = $colorIndex;
            self::$usedIndices[] = $colorIndex;
        }

        $color = self::$baseColors[$colorIndex];
        return "rgba({$color['r']}, {$color['g']}, {$color['b']}, {$alpha})";
    }

    public static function resetAssignments(): void
    {
        self::$assignedColors = [];
        self::$usedIndices = [];
    }
}
