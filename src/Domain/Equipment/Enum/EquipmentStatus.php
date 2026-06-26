<?php

namespace App\Domain\Equipment\Enum;

enum EquipmentStatus: string
{
    case InProgress = "In progress";
    case Completed = "Completed";
}
