<?php

declare(strict_types=1);

namespace App\Http\Requests\HostComment;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule;

class CreateHostCommentRequest extends FormRequest
{

    public function rules() : array
    {
        return [
        	'localCommentId' => 'required|integer',
            'text' => 'required|min:1'
        ];
    }
}
