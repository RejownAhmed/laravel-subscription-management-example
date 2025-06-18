<?php

namespace App\Services;

use App\Services\Concerns\HasAttrs;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    use HasAttrs;

    protected Model $model;


    public function setModel(Model $model): BaseService
    {
        $this->model = $model;
        return $this;
    }


    public function getModel(): Model
    {
        return $this->model;
    }

    public function save($options = []): Model
    {
        $this->model
            ->fill(count($options) ? $options : request()->all())
            ->save();
        return $this->model;
    }

    public function find($id): Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return $this->model = $this->model::query()->find($id);
    }


    public function delete(): static
    {
        $this->model->delete();
        return $this;
    }

    public function __call($method, $arguments)
    {
        return $this->model->{$method}(...$arguments);
    }
}
