<?php

namespace App\Service;

use phpDocumentor\Reflection\Types\Integer;

class DateTimeService
{
  public function isWeekEnd(\DateTimeImmutable $date)
  {
    $dayOfWeek = $date->format('N');
    if ($dayOfWeek == 6 || $dayOfWeek == 7) {
      return true;
    }
    return false;
  }

  public function getMinutesDiff(\DateTimeImmutable $start, \DateTimeImmutable $end)
  {
    $interval = $start->diff($end);
    // En Minutes
    return $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
  }

  public function getSecondsDiff(\DateTimeImmutable $start, \DateTimeImmutable $end)
  {
    $interval = $start->diff($end);
    // En Secondes
    return $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
  }

  public function convertToSeconds(int $hours)
  {
    return $hours * 3600;
  }
}