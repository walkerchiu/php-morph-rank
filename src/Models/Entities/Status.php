<?php

namespace WalkerChiu\MorphRank\Models\Entities;

use WalkerChiu\Core\Models\Entities\Entity;
use WalkerChiu\Core\Models\Entities\LangTrait;

class Status extends Entity
{
    use LangTrait;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->table = config('wk-core.table.morph-rank.statuses');
        $this->fillable = array_merge($this->fillable, [
            'host_type', 'host_id',
            'serial',
            'identifier',
            'morph_type', 'morph_id',
            'order'
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get it's lang entity.
     *
     * @return Lang
     */
    public function lang()
    {
        if (config('wk-core.onoff.core-lang_core') || config('wk-morph-rank.onoff.core-lang_core'))
            return config('wk-core.class.core.langCore');
        else
            return config('wk-core.class.morph-rank.statusLang');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function langs()
    {
        if (config('wk-core.onoff.core-lang_core') || config('wk-morph-rank.onoff.core-lang_core'))
            return $this->langsCore();
        else
            return $this->hasMany(config('wk-core.class.morph-rank.statusLang'), 'morph_id', 'id');
    }

    /**
     * Get the owning host model.
     */
    public function host()
    {
        return $this->morphTo();
    }

    /**
     * Get the owning morph model.
     */
    public function morph()
    {
        return $this->morphTo();
    }
}
