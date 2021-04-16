<?php

namespace SIMP2\SDK\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Alert()
 * @method static static Critical()
 * @method static static Error()
 * @method static static Warning()
 * @method static static Notice()
 * @method static static Info()
 * @method static static Debug()
 */
final class LogLevel extends Enum
{
    const Critical = 6;
    const Error = 5;
    const Warning = 4;
    const Alert = 3;
    const Info = 2;
    const Notice = 1;
    const Debug = 0;
}
