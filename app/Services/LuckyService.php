<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Results;
use App\Traits\FormatDate;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LuckyService
{

    const WIN = 'Win';
    const LOSS = 'Loss';

    use FormatDate;

    /**
     * @param Registration $registration
     * @return array
     * @throws \Random\RandomException
     */
    public function generateLucky(Registration $registration): array
    {
        $number = random_int(1, 1000);
        $isWin = $number % 2 === 0;

        $percent = $this->calcPercent($number);

        $amount = $isWin ? round($number * $percent, 2) : 0.0;

        $result = Results::create([
            'registration_id' => $registration->id,
            'number' => $number,
            'is_win' => $isWin,
            'amount' => $amount,
        ]);

        return [
            'number' => $number,
            'result' => $isWin ? self::WIN : self::LOSS,
            'amount' => $amount,
            'created_at' => $this->formatDate($result->created_at),
        ];
    }

    /**
     * @param Registration $registration
     * @param int $limit
     * @return array
     */
    public function getHistory(Registration $registration, int $limit = 3): array
    {
        $items = $registration->results()
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['number','is_win','amount','created_at']);

        return $items->map(function ($i) {
            return [
                'number' => $i->number,
                'result' => $i->is_win ? self::WIN : self::LOSS,
                'amount' => (float)$i->amount,
                'created_at' => $this->formatDate($i->created_at),
            ];
        })->toArray();
    }

    /**
     * @param Registration $registration
     * @return array
     * @throws \Exception
     */
    public function regenerateLink(Registration $registration): array
    {
        if (!$registration->is_active || Carbon::now()->gt($registration->expired_at)) {
            throw new \Exception('Cannot regenerate: link inactive or expired');
        }

        $registration->token = Str::uuid()->toString();
        $registration->expired_at = $this->formatDate(now()->addDays(7));
        $registration->is_active = true;
        $registration->save();

        return [
            'link' => url('/lucky/'.$registration->token),
            'token' => $registration->token,
            'expired_at' => $registration->expired_at,
        ];
    }

    /**
     * @param Registration $registration
     * @return string[]
     */
    public function deactivateLink(Registration $registration): array
    {
        $registration->is_active = false;
        $registration->save();

        return ['message' => 'Link deactivated'];
    }

    /**
     * Calculate percentage based on the given number.
     *
     * @param int $number
     * @return float
     */
    private function calcPercent(int $number): float
    {
        return match(true) {
            $number > 900 => 0.70,
            $number > 600 => 0.50,
            $number > 300 => 0.30,
            default => 0.10,
        };
    }
}
