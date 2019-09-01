<?php

namespace App\Services\Utils;

class UtilsService
{
    /**
     * Get first character of each word.
     *
     * @param string $string
     *
     * @return string
     */
    public function getFirstCharactersOfEachWord(string $string)
    {
        $result = '';
        foreach (preg_split('#[^a-z]+#i', $string, -1, PREG_SPLIT_NO_EMPTY) as $word) {
            $result .= $word[0];
        }
        return $result;
    }

    /**
     * Prepare id before validate does already exist.
     *
     * @param int    $countIds
     * @param string $company
     *
     * @return string
     */
    public function getPreparedID(int $countIds, string $company)
    {
        if ($countIds < 10) {
            return $company . '-' . '00' . $countIds;
        }

        if ($countIds < 100) {
            return $company . '-' . '0' . $countIds;
        }

        return $company . '-' . $countIds;
    }
}
