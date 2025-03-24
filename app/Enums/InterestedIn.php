<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class InterestedIn extends Enum
{
    const PERSONAL_TRAINING = 'Personal Traning';
    const GYM_ACCESS = 'Gym Access';
    const GROUP_CLASSES = 'Group Classes';

    const TRANSFORMATION_PACK = 'Transformation Pack';

    const OTHER = "Other";
}
