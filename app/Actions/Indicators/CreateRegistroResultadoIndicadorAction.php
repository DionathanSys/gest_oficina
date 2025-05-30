<?php

namespace App\Actions\Indicators;

use App\Models\IndicatorResult;

class CreateRegistroResultadoIndicadorAction
{
    public static function exec(IndicatorResult $record)
    {
        $managers = $record->indicator->managers;
        $managers->each(function ($manager) use ($record) {
            if($manager->id != $record->manager_id){
                $data = $record->toArray();
                unset($data['indicator'], $data['id']);
                $data['manager_id'] = $manager->id;
                IndicatorResult::create($data);
            }
        });

    }
}
