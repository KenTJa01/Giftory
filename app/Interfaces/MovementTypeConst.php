<?php

namespace App\Interfaces;

interface MovementTypeConst
{
    /**
     * List movement types code
     */
    public const TRANSFERIN_MOVEMENT = 'TRF-IN';

    public const TRANSFEROUT_MOVEMENT = 'TRF-OUT';

    public const RECEIVING_MOVEMENT = 'REC';

    public const EXPENDING_MOVEMENT = 'EXP';

    public const ADJUSTMENT_MOVEMENT = 'ADJ';

    public const STOCKOPNAME_MOVEMENT = 'SO';

    public const OPENINGBALANCE_MOVEMENT = 'OB';
    public const RETURN_MOVEMENT = 'RET';
}
