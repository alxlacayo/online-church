<?php

declare(strict_types=1);

namespace App\Http\Requests\HostComment;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule;

class GetAllHostCommentsRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'maxid' => 'integer'
        ];
    }
}
