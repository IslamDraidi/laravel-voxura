<?php

namespace App\Services;

class MessageFilterService
{
    private array $linkPatterns = [
        '/https?:\/\//i',
        '/www\./i',
        '/\.com/i',
        '/\.net/i',
        '/\.org/i',
        '/bit\.ly/i',
        '/tinyurl/i',
    ];

    private array $spamKeywords = [
        'click here', 'free money', 'winner',
        'congratulations', 'prize', 'lottery',
        'nigerian prince', 'wire transfer',
        'bank account', 'credit card number',
        'social security', 'password',
        'bitcoin', 'cryptocurrency', 'invest now',
        'make money fast', 'work from home',
        'earn $', 'earn money',
    ];

    private array $profanityList = [];

    private array $personalInfoPatterns = [
        '/\b\d{4}[\s-]?\d{4}[\s-]?\d{4}[\s-]?\d{4}\b/',
        '/\b\d{3}-\d{2}-\d{4}\b/',
        '/\b\+?[\d\s\-\(\)]{10,15}\b/',
    ];

    public function analyze(string $message, string $subject = ''): array
    {
        $fullText   = strtolower($message . ' ' . $subject);
        $flags      = [];
        $shouldFlag = false;

        foreach ($this->linkPatterns as $pattern) {
            if (preg_match($pattern, $fullText)) {
                $flags[]    = 'contains_link';
                $shouldFlag = true;
                break;
            }
        }

        foreach ($this->spamKeywords as $keyword) {
            if (str_contains($fullText, $keyword)) {
                $flags[]    = 'spam_keyword:' . $keyword;
                $shouldFlag = true;
            }
        }

        foreach ($this->profanityList as $word) {
            if (str_contains($fullText, $word)) {
                $flags[]    = 'profanity';
                $shouldFlag = true;
                break;
            }
        }

        foreach ($this->personalInfoPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                $flags[]    = 'personal_info';
                $shouldFlag = true;
                break;
            }
        }

        $wordCount = str_word_count($message);
        if ($wordCount < 3) {
            $flags[] = 'too_short';
        }
        if ($wordCount > 500) {
            $flags[]    = 'too_long';
            $shouldFlag = true;
        }

        return [
            'should_flag'  => $shouldFlag,
            'flags'        => $flags,
            'auto_approve' => !$shouldFlag,
            'word_count'   => $wordCount,
        ];
    }
}
