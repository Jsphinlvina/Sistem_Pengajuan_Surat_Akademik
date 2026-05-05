<?php

namespace App\Concerns;

trait SmartDelete
{
    public function smartDelete(array $relations): bool
    {
        foreach ($relations as $relation) {

            if (!method_exists($this, $relation)) {
                throw new \Exception("Relation {$relation} tidak ditemukan");
            }

            if ($this->{$relation}()->exists()) {
                return false;
            }
        }

        $this->delete();
        return true;
    }
}
