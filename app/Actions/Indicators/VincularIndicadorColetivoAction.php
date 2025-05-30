<?php

namespace App\Actions\Indicators;

use App\Models\Indicator;
use App\Models\Manager;

class VincularIndicadorColetivoAction
{
    public static function execute(Indicator $indicator)
    {

        $managers = Manager::all();;

        if ($managers->isNotEmpty()) {
            $indicator->managers()->syncWithoutDetaching($managers->pluck('id')->toArray());

            return;
        }

    }
}
