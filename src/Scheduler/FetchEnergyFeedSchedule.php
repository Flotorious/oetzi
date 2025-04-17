<?php

namespace App\Scheduler;

use App\Message\FetchEnergyFeedMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\Trigger\CronExpressionTrigger;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule('FetchEnergyFeedScheduler')]
final class FetchEnergyFeedSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                RecurringMessage::every('5 minutes', new FetchEnergyFeedMessage())

            )
            ->stateful($this->cache);
    }
}
