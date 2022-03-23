<?php

namespace WalkerChiu\MorphRank\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class LevelLang extends Lang
{
    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.morph-rank.levels_lang');

        parent::__construct($attributes);
    }
}
