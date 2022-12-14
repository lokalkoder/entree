<?php

namespace Lokalkoder\Entree\Services;

class ShortCodeGenerator
{
    protected string $description = '';

    protected int $maxChar = 5;

    /**
     * @param  string  $description
     * @param  int  $maxChar
     */
    public function __construct(string $description, int $maxChar = 5)
    {
        $this->description = $description;
        $this->maxChar = $maxChar;
    }

    /**
     * Generate reference code.
     *
     * @param  string|null  $code
     * @return string
     */
    public function generateReferenceCode(?string $code): string
    {
        $ref = $this->generateShortCode();

        if ($code === null) {
            return strtoupper($ref);
        }

        $originalCode = $this->cleanString($code);

        return (strlen($originalCode) < 5 && ! empty($originalCode)) ? $originalCode : strtoupper($ref);
    }

    /**
     * Generate short code.
     *
     * @return string
     */
    public function generateShortCode(): string
    {
        $string = $this->cleanString($this->description);

        $array = str_split($string);

        $descriptionLength = count($array);

        if ($descriptionLength > $this->maxChar) {
            $med = floor($descriptionLength / 2);

            $mid = floor($med / 2);

            $code = trim($array[0].$array[$mid].$array[$med].$array[$med + $mid].$array[$descriptionLength - 1]);
        } else {
            $code = trim($string);
        }

        return $code;
    }

    /**
     * Sanitize string.
     *
     * @param  string  $string
     * @return string
     */
    protected function cleanString(string $string): string
    {
        return preg_replace('/[^A-Za-z]/', '', $string);
    }
}
