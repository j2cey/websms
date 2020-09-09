<?php


namespace App\Traits;

use Illuminate\Support\Carbon;

trait SuspendableTrait
{
    public function suspend() {
        $this->update([
            'suspended_at' => Carbon::now(),
        ]);
    }

    public function unSuspend() {
        $this->update([
            'suspended_at' => null,
        ]);
    }
}
