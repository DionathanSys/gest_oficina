<?php

namespace App\Actions;

use App\Models\IndicatorResult;

class CreateRegistroResultadoIndicadorAction
{
    public static function exec(IndicatorResult $record)
    {
        dump('record', $record);
        $managers = $record->indicator->managers;
        dump('managers', $managers);
        $managers->each(function ($manager) use ($record) {
            if($manager->id != $record->manager_id){
                dump('manager diferente', $manager->nome);
                $data = $record->toArray();
                $data['manager_id'] = $manager->id;
                dump('indicatorResult', $data);
                $d = IndicatorResult::create($data);
                dd($d);
            }
        });

        $managers->load('indicatorResults');

        dd($managers);
    }
}
