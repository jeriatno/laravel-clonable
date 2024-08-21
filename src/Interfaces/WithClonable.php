<?php

namespace App\Interfaces;

interface WithClonable
{
    /**
     * Get the relationships that should be cloned.
     *
     * @return array
     */
    public function getCloneableRelations(): array;

    /**
     * Get custom data for the clone.
     *
     * @return array
     */
    public function getCustomCloneData(): array;
}
