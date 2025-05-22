<?php

namespace App\Actions;

use App\Models\IndicatorResult;

class CreateRegistroResultadoIndicadorAction
{
    public static function exec(IndicatorResult $record)
    {
        dump($record->manager_id);

        $managers = $record->indicator->managers;

        $managers->each(function ($manager) use ($record) {
            if($manager->id != $record->manager_id){
                $indicatorResult = $record->replicate();
                $indicatorResult->manager_id = $manager->id;
                $indicatorResult->save();
                dump($manager->id);
                return;
            }
        });

        dd($managers);

        return \App\Models\IndicatorResult::create($record);
    }
}
