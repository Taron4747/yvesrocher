<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MaxActiveLimit implements Rule
{
    protected $model;
    protected $id; // ID текущей записи (для обновления)

    public function __construct(string $model, $id = null)
    {
        $this->model = $model;
        $this->id = $id;
    }

    public function passes($attribute, $value)
    {
        if ($value != 1) {
            return true;
        }

        $query = DB::table((new $this->model)->getTable())
            ->where('is_active', 1);

        if ($this->id) {
            $query->where('id', '!=', $this->id);
        }

        return $query->count() < 6;
    }

    public function message()
    {
        return 'Допускается не более 5 активных записей.';
    }
}

