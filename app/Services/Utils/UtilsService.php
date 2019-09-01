<?php

namespace App\Services\Utils;

class UtilsService
{
    /**
     * Prepare id before validate does already exist.
     *
     * @param int    $countIds
     * @param mix $company
     *
     * @return string
     */
    public function getPreparedID(int $countIds, $company)
    {
        if ($countIds < 10) {
            return $company . '00' . $countIds;
        }

        if ($countIds < 100) {
            return $company . '0' . $countIds;
        }

        return $company . $countIds;
    }
}
