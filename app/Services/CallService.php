<?php

namespace App\Services;

use App\Models\Call;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CallService
{
    protected $daysLimit;
    protected $maxHoursPerWeek;

    public function __construct($daysLimit, $maxHoursPerWeek)
    {
        $this->daysLimit = $daysLimit;
        $this->maxHoursPerWeek = $maxHoursPerWeek;
    }

    /**
     * Получает доступные слоты для инвестора.
     */
    public function getAvailableSlots($investorId)
    {
        return Call::getAvailableSlots($investorId, $this->daysLimit, $this->maxHoursPerWeek);
    }

    /**
     * Проверяет, доступен ли слот для бронирования.
     */
    private function isSlotAvailable($investorId, $startTime)
    {
        return true;
    }

    /**
     * Бронирует слот для пользователя.
     */
    public function bookSlot($investorId, $userId, $startTime)
    {
        if (!$this->isSlotAvailable($investorId, $startTime)) {
            return false;
        }

        $start = Carbon::parse($startTime);
        $end = $start->copy()->addMinutes(60);

        return Call::create([
            'investor_id' => $investorId,
            'user_id' => $userId,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'booked',
        ]);
    }
    /**
     * Отменяет забронированный звонок.
     */
    public function cancelCall($callId)
    {
        $call = Call::find($callId);
        if ($call) {
            $call->delete();
            return true;
        }
        return false;
    }
}
