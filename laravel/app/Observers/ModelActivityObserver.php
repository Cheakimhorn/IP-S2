<?php

namespace App\Observers;
use App\Models\ActivityLog;
use App\Models\Order;

class ModelActivityObserver
{
    /**
     * Handle the "created" event.
     */
    public function created(Order $order): void
    {
        ActivityLog::create([
            'model'    => get_class($order),
            'model_id' => $order->id,
            'action'   => 'created',
            'changes'  => $order->toArray(),
        ]);
    }

    /**
     * Handle the "updated" event.
     */
    public function updated(Order $order): void
    {
        ActivityLog::create([
            'model'    => get_class($order),
            'model_id' => $order->id,
            'action'   => 'updated',
            'changes'  => [
                'old' => $order->getOriginal(),
                'new' => $order->getChanges(),
            ],
        ]);
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted(Order $order): void
    {
        ActivityLog::create([
            'model'    => get_class($order),
            'model_id' => $order->id,
            'action'   => 'deleted',
            'changes'  => $order->toArray(),
        ]);
    }
}
