<?php

namespace App\Twig;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateTimeExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('timeAgo', [$this, 'timeAgoFilter']),
        ];
    }

    /**
     * @param \DateTime $date
     * @return string
     */
    public function timeAgoFilter(\DateTime $date): string
    {
        return Carbon::instance($date)->diffForHumans();
    }
}
