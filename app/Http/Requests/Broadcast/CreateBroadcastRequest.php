<?php

declare(strict_types=1);

namespace App\Http\Requests\Broadcast;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Validation\Rule;

class CreateBroadcastRequest extends FormRequest
{

    public function rules() : array
    {
        return [
            'name' => 'required|max:255',
            'day' => 'required_if:recurring,1|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time' => 'required_if:recurring,1|date_format:"H:i:s',
            'live' => 'required|boolean',
            'enabled' => 'required|boolean',
            'recurring' => 'required|boolean',
            'embed_code' => 'required_if:live,1',
            'starts_at' => 'required_if:recurring,0|date_format:Y-m-d H:i:s'
        ];
    }
}
