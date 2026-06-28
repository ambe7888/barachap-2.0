<?php

namespace App\Enums;

enum RefundStatusEnum : int
{
    case PENDING = 0;
    case COMPLETE = 2;
    case APPROVE = 1;
    case CANCEL = 3;

   
    public function label()
    {
        return match ($this) {
            RefundStatusEnum::COMPLETE => __('Complete'),
            RefundStatusEnum::APPROVE => __('Approve'),
            RefundStatusEnum::CANCEL => __('Cencel'),
            default => __('Pending')
        };
    }

}