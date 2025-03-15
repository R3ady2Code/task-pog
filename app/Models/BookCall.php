<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id',
        'user_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Получить доступные слоты для бронирования.
     *
     * @param int $investorId
     * @param int $daysLimit
     * @param int $maxHoursPerWeek
     * @return array
     */
    public static function getAvailableSlots($investorId, $daysLimit)
    {
        $now = Carbon::now()->addHours(12);
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $weekStart->copy()->addDays($daysLimit);

        $slots = [];

        $bookedCalls = self::where('investor_id', $investorId)
            ->whereBetween('start_time', [$weekStart, $weekEnd])
            ->get();

        $hourlyAvailability = [];
        foreach ($bookedCalls as $call) {
            $hour = $call->start_time->format('Y-m-d H:00:00');
            if (!isset($hourlyAvailability[$hour])) {
                $hourlyAvailability[$hour] = [];
            }
            $hourlyAvailability[$hour][] = $call;
        }

        for ($day = 0; $day < $daysLimit; $day++) {
            $date = $weekStart->copy()->addDays($day);

            // Получаем рабочие часы через модель Investor
            // Логика получения слотов на каждый доступный день, в рабочие часы
        }

        return $slots;
    }
}
