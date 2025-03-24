<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LeadSource extends Enum
{
    const WEBSITE = 'web site';
    const WALK_IN = 'walk in';
    const SOCIAL_MEDIA = 'social media';

    const REFERRAL = 'referral';
    const EVENT = 'event';
    const OTHER = 'other';


}
