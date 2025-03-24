<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ContactMethod extends Enum
{
    const PHONE = 'Phone';
    const EMAIL = 'Email';
    const WHATSUP = 'WhatsApp';
    const SMS = "SMS";

}
