<?php


namespace Solobea\Helpers\data;


class Sanitizer
{
    public static function sanitize($data): string
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    public static function is_valid_message($text): bool
    {
        // Minimum length to be considered valid
        if (strlen(trim($text)) < 4) return false;

        // Load a list of known Swahili & English keywords
        $known_words = array_merge(
            explode("\n", file_get_contents("dictionary/kamusi.txt")),
            explode("\n", file_get_contents("dictionary/dictionary.txt"))
        );

        // Normalize and split the input text
        $words = preg_split('/[\s,.;:!?]+/', strtolower($text));

        // Check if at least 50% of the words are recognized
        $valid_count = 0;
        foreach ($words as $word) {
            if (in_array($word, $known_words)) {
                $valid_count++;
            }
        }

        return ($valid_count / max(count($words), 1)) >= 0.5;
    }

    public static function clean_for_json($text) {
        // Step 1: Fix encoding issues (auto-replaces broken sequences)
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        // Step 2: Replace curly/smart quotes with standard ones
        $text = str_replace(
            ['“','”','‘','’'],
            ['"','"',"'", "'"],
            $text
        );

        // Step 3: Remove control characters except newline (10), carriage return (13), tab (9)
        // Step 4: Optionally strip emojis (keep if you want!)
        // $text = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $text); // Emojis range

        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
    }

}