<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MembershipBase extends Enum
{
    public const DURATION_BASED = "Duration Based";
    public const SESSION_BASED = "Session Based";
    public const CLASSES_BASED = "Classes Based";

}
