<?php

namespace App\Interfaces;

interface ProfileConst
{
    /**
     * List or user profiles
     */
    public const SUPERUSERPROFILE = 'SU';

    public const STOREMANAGERPROFILE = 'SM';

    public const REGIONALMARKETINGPROFILE = 'RMKT';

    public const ITCABPROFILE = 'ITCB';

    public const FINANCEPROFILE = 'FNA'; /** Finance cabang */

    public const PICGUDANGPROFILE = 'PIC'; /** PIC Gudang */

    public const ADMINPROFILE = 'ADM'; /** User pusat */

    public const RECEIVINGPROFILE = 'REC';

    public const OPERASIONALCABPROFILE = 'OPR'; /** CSO / Buyer */

    public const BUYERCABPROFILE = 'BYR';
    
    public const SUPERUSERMARKETINGPROFILE = 'SMKT';
}
