<?php

declare(strict_types=1);

namespace App\Services\IntroVideo;

use App\IntroVideo;

class GetIntroVideo
{

    public function execute() : IntroVideo
    {   
        return IntroVideo::where('enabled', 1)
            ->orderByDesc('id')
            ->first();
    }
}
