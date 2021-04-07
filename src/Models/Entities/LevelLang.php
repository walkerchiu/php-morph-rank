<?php

namespace WalkerChiu\MorphRank\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class LevelLang extends Lang
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->table = config('wk-core.table.morph-rank.levels_lang');

        parent::__construct($attributes);
    }
}
