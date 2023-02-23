<?php declare(strict_types=1);

return [

    /**
     * Add an additional second for every 100th word of the toast message.
     * Recommended to keep it on, though you may wish to disable this.
     *
     * Supported: true | false
     */
    'accessibility' => true,

    /**
     * The on-screen duration of each toast.
     *
     * Minimum: 3000 (in milliseconds)
     */
    'duration' => 3000,

    /**
     * The on-screen position of each toast.
     *
     * Supported: "center", "left" or "right"
     */
    'position' => 'right',

    /**
     * Whether messages passed as translation keys should be translated automatically.
     * While this is a very useful default behaviour, you may wish to disable this.
     *
     * Supported: true | false
     */
    'translate' => true,
];
