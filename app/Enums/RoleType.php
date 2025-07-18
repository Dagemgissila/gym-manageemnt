<?php
namespace App\Enums;

use BenSampo\Enum\Enum;

final class RoleType extends Enum
{
    public const ADMIN = "admin";
    public const STAFF = "staff";
    public const TRAINER = "trainer"; 
    public const MEMBER = "member"; 
}
