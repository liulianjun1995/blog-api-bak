<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use AdminBuilder, ModelTree {
        ModelTree::boot as treeBoot;
    }
    protected $table = 'categorys';

    protected static function boot()
    {
        static::treeBoot();
    }

    public function getTree($field = ['*'], $parentId = 0)
    {
        $branch = [];

        $nodes = self::query()->where('show', 1)->orderBy($this->orderColumn)->get($field)->toArray();

        foreach ($nodes as $node) {
            if ($node[$this->parentColumn] == $parentId) {
                $children = $this->buildNestedArray($nodes, $node[$this->getKeyName()]);

                if ($children) {
                    $node['children'] = $children;
                }

                $branch[] = $node;
            }
        }

        return $branch;
    }

    public function posts()
    {
        return $this->hasMany(Posts::class, 'category_id');
    }
}
