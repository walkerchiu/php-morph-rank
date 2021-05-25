<?php

namespace WalkerChiu\MorphRank\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormHasHostTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryHasHostTrait;

class LevelRepository extends Repository
{
    use FormHasHostTrait;
    use RepositoryHasHostTrait;

    protected $entity;

    public function __construct()
    {
        $this->entity = App::make(config('wk-core.class.morph-rank.level'));
    }

    /**
     * @param String  $host_type
     * @param String  $host_id
     * @param String  $code
     * @param Array   $data
     * @param Int     $page
     * @param Int     $nums per page
     * @param Boolean $is_enabled
     * @param String  $target
     * @param Boolean $target_is_enabled
     * @param Boolean $toArray
     * @return Array|Collection
     */
    public function list($host_type, $host_id, String $code, Array $data, $page = null, $nums = null, $is_enabled = null, $target = null, $target_is_enabled = null, $toArray = true)
    {
        $this->assertForPagination($page, $nums);

        if (empty($host_type) || empty($host_id)) {
            $entity = $this->entity;
        } else {
            $entity = $this->baseQueryForRepository($host_type, $host_id, $target, $target_is_enabled);
        }
        if ($is_enabled === true)      $entity = $entity->ofEnabled();
        elseif ($is_enabled === false) $entity = $entity->ofDisabled();

        $data = array_map('trim', $data);
        $records = $entity->with(['langs' => function ($query) use ($code) {
                                $query->ofCurrent()
                                      ->ofCode($code);
                             }])
                             ->unless(empty(config('wk-core.class.morph-tag.tag')), function ($query) {
                                 return $query->with(['tags', 'tags.langs']);
                             })
                            ->when($data, function ($query, $data) {
                                return $query->unless(empty($data['id']), function ($query) use ($data) {
                                            return $query->where('id', $data['id']);
                                        })
                                        ->unless(empty($data['serial']), function ($query) use ($data) {
                                            return $query->where('serial', $data['serial']);
                                        })
                                        ->unless(empty($data['identifier']), function ($query) use ($data) {
                                            return $query->where('identifier', $data['identifier']);
                                        })
                                        ->unless(empty($data['morph_type']), function ($query) use ($data) {
                                            return $query->where('morph_type', $data['morph_type']);
                                        })
                                        ->unless(empty($data['morph_id']), function ($query) use ($data) {
                                            return $query->where('morph_id', $data['morph_id']);
                                        })
                                        ->unless(empty($data['order']), function ($query) use ($data) {
                                            return $query->where('order', $data['order']);
                                        })
                                        ->unless(empty($data['name']), function ($query) use ($data) {
                                            return $query->whereHas('langs', function($query) use ($data) {
                                                $query->ofCurrent()
                                                      ->where('key', 'name')
                                                      ->where('value', 'LIKE', "%".$data['name']."%");
                                            });
                                        })
                                        ->unless(empty($data['description']), function ($query) use ($data) {
                                            return $query->whereHas('langs', function($query) use ($data) {
                                                $query->ofCurrent()
                                                      ->where('key', 'description')
                                                      ->where('value', 'LIKE', "%".$data['description']."%");
                                            });
                                        })
                                        ->unless(empty($data['categories']), function ($query) use ($data) {
                                            return $query->whereHas('categories', function($query) use ($data) {
                                                $query->ofEnabled()
                                                      ->whereIn('id', $data['categories']);
                                            });
                                        })
                                        ->unless(empty($data['tags']), function ($query) use ($data) {
                                            return $query->whereHas('tags', function($query) use ($data) {
                                                $query->ofEnabled()
                                                      ->whereIn('id', $data['tags']);
                                            });
                                        });
                            })
                            ->orderBy('order', 'ASC')
                            ->get()
                            ->when(is_integer($page) && is_integer($nums), function ($query) use ($page, $nums) {
                                return $query->forPage($page, $nums);
                            });
        if ($toArray) {
            $list = [];
            foreach ($records as $record) {
                $data = $record->toArray();
                array_push($list,
                    array_merge($data, [
                        'name'        => $record->findLangByKey('name'),
                        'description' => $record->findLangByKey('description')
                    ])
                );
            }

            return $list;
        } else {
            return $records;
        }
    }

    /**
     * @param Level $entity
     * @param Array|String $code
     * @return Array
     */
    public function show($entity, $code)
    {
    }
}
