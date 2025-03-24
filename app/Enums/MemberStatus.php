<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MemberStatus extends Enum
{
    const PROSPECT = 'prospect';
    const TRIAL = 'trial';
    const LIVE_MEMBER = 'live member';
    const EX_MEMBER = 'ex member';

    const SUSPENDED = 'suspended';

    const INACTIVE = 'inactive';

    const TERMINATED = 'terminated';

}
