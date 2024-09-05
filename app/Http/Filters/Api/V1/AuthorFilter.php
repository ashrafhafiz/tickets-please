<?php

namespace App\Http\Filters\Api\V1;

use Illuminate\Http\Request;

class AuthorFilter extends QueryFilter
{
    protected $sortable = [
        'id',
        'email',
        'name',
        'createdAt' => 'created_at',
        'updatedAT' => 'updated_at'
    ];

    public function include($value)
    {
        return $this->builder->with($value);
    }
    public function id($value)
    {
        return $this->builder->whereIn('id',explode(',',$value));
    }

    public function email($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('email', 'like', $likeStr);
    }

    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('name', 'like', $likeStr);
    }

    public function createdAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1){
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $dates);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1){
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $dates);
    }

}
