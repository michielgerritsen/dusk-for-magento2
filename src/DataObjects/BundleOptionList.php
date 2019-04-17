<?php
/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */

namespace ControlAltDelete\DuskForMagento2\DataObjects;

use ControlAltDelete\DuskForMagento2\Exceptions\InvalidBundleOption;

class BundleOptionList
{
    /**
     * @var BundleOption[]
     */
    private $options = [];

    public function __construct(array $options)
    {
        foreach ($options as $option) {
            if (!$option instanceof BundleOption) {
                throw new InvalidBundleOption;
            }
        }

        $this->options = $options;
    }

    /**
     * @return BundleOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
