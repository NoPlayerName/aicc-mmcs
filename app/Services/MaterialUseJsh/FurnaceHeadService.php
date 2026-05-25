<?php

namespace App\Services\MaterialUseJsh;

use App\Models\Jsh\MaterialUse\FurnaceHead;
use Illuminate\Support\Facades\Auth;

class FurnaceHeadService
{
    public function createFurnaceHead($furnace, $date, $shift)
    {
        $data = [
            'furnace' => $furnace,
            'date' => $date,
            'shift' => $shift,
            'created_by' => Auth::user()->usr,
        ];

        return FurnaceHead::create($data);
    }

    public function getFurnaceHeadByDateShift($date, $shift)
    {
        return FurnaceHead::where('date', $date)
            ->where('shift', $shift)
            ->whereIn('furnace', [4, 5])
            ->get();
    }
}