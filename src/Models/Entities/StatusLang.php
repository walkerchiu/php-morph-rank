<?php

namespace WalkerChiu\MorphRank\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class StatusLang extends Lang
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->table = config('wk-core.table.morph-rank.statuses_lang');

        parent::__construct($attributes);
    }
}
