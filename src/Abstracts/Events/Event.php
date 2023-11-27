<?php

declare(strict_types=1);

namespace LashchenkoV\Porto\Abstracts\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}
