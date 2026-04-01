<?php

namespace App\Services\Lab;

use App\Models\Lab\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log an activity
     *
     * @param string $action The action performed (created, updated, deleted, approved, rejected, etc.)
     * @param string $description Human-readable description of the action
     * @param Model $subject The subject of the action (polymorphic)
     * @param array $properties Additional properties (old/new values, etc.)
     * @param User|null $user The user performing the action (defaults to auth user)
     * @return ActivityLog
     */
    public static function log(
        string $action,
        string $description,
        Model $subject,
        array $properties = [],
        ?User $user = null
    ): ActivityLog
    {
        $user = $user ?? Auth::user();

        return ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'description' => $description,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'properties' => $properties,
            'ip_address' => request()->ip()
        ]);
    }

    /**
     * Log inventory condition change
     */
    public static function logInventoryConditionChange(Model $inventaris, string $oldKondisi, string $newKondisi, ?User $user = null): ActivityLog
    {
        return self::log(
            'updated_condition',
            "Mengubah kondisi alat '{$inventaris->nama_inventaris}' dari {$oldKondisi} ke {$newKondisi}",
            $inventaris,
            [
                'old_kondisi' => $oldKondisi,
                'new_kondisi' => $newKondisi
            ],
            $user
        );
    }

    /**
     * Log inventory transfer
     */
    public static function logInventoryTransfer(Model $inventaris, $oldLaborId, $newLaborId, ?User $user = null): ActivityLog
    {
        return self::log(
            'transferred',
            "Memindahkan alat '{$inventaris->nama_inventaris}' ke laboratorium lain",
            $inventaris,
            [
                'old_labor_id' => $oldLaborId,
                'new_labor_id' => $newLaborId
            ],
            $user
        );
    }

    /**
     * Log borrowing approval
     */
    public static function logBorrowingApproval(Model $borrowing, string $type = 'alat', ?User $user = null): ActivityLog
    {
        return self::log(
            'approved',
            "Menyetujui peminjaman {$type}",
            $borrowing,
            ['status' => 'approved'],
            $user
        );
    }

    /**
     * Log borrowing rejection
     */
    public static function logBorrowingRejection(Model $borrowing, string $reason, string $type = 'alat', ?User $user = null): ActivityLog
    {
        return self::log(
            'rejected',
            "Menolak peminjaman {$type}",
            $borrowing,
            [
                'status' => 'rejected',
                'reason' => $reason
            ],
            $user
        );
    }

    /**
     * Log damage report creation
     */
    public static function logDamageReport(Model $laporan, ?User $user = null): ActivityLog
    {
        return self::log(
            'reported_damage',
            "Melaporkan kerusakan alat",
            $laporan,
            ['tingkat_kerusakan' => $laporan->tingkat_kerusakan],
            $user
        );
    }
}
