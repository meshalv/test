<?php

namespace App\Services;

use App\Models\Registration;
use App\Traits\FormatDate;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class RegistrationService
{
    use FormatDate;

    /**
     * @param array $data
     * @return Registration
     */
    public function create(array $data): Registration
    {
        return Registration::updateOrCreate(
            ['phone_number' => $data['phone_number']],
            [
                'username' => $data['username'],
                'token' => Str::uuid(),
                'expired_at' => $this->formatDate(Carbon::now()->addDays(7)),
                'is_active' => true,
            ]
        );
    }
}
