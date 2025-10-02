<?php

namespace App\Interfaces;

interface PermissionConst
{
    /**
     * List user permissions for transfer
     */
    public const TRANSFER_LIST = 'transfer.list';

    public const TRANSFER_CREATE = 'transfer.create';

    public const TRANSFER_APPROVAL = 'transfer.approval';

    public const TRANSFER_PRINT = 'transfer.print';

    /**
     * List user permissions for receiving
     */
    public const RECEIVING_LIST = 'receiving.list';

    public const RECEIVING_CREATE = 'receiving.create';

    /**
     * List user permissions for return
     */
    public const RETURN_LIST = 'return.list';

    public const RETURN_CREATE = 'return.create';

    public const RETURN_APPROVAL = 'return.approval';

    /**
      * List user permissions for expending
      */
    public const EXPENDING_LIST = 'expending.list';

    public const EXPENDING_CREATE = 'expending.create';

    public const EXPENDING_APPROVAL = 'expending.approval';

    /**
     * List user permissions for adjustment
     */
    public const ADJUSTMENT_LIST = 'adjustment.list';

    public const ADJUSTMENT_CREATE = 'adjustment.create';

    /**
     * List user permissions for stock opname
     */
    public const STOCKOPNAME_LIST = 'stockopname.list';

    public const STOCKOPNAME_CREATE = 'stockopname.create';

    /**
     * List user permissions for data master
     */
    public const MASTER_USER_LIST = 'master.user.list';

    public const MASTER_USER_CREATE = 'master.user.create';

    public const MASTER_USER_EDIT = 'master.user.edit';

    public const MASTER_USER_RESET = 'master.user.reset';

    public const MASTER_PRODUCT_LIST = 'master.product.list';

    public const MASTER_PRODUCT_CREATE = 'master.product.create';

    public const MASTER_PRODUCT_EDIT = 'master.product.edit';

    public const MASTER_PROFILE_LIST = 'master.profile.list';

    public const MASTER_PROFILE_CREATE = 'master.profile.create';

    public const MASTER_PROFILE_EDIT = 'master.profile.edit';

    public const MASTER_LOCATION_LIST = 'master.location.list';

    public const MASTER_LOCATION_CREATE = 'master.location.create';

    public const MASTER_LOCATION_EDIT = 'master.location.edit';

    public const MASTER_SITE_LIST = 'master.site.list';

    public const MASTER_SUPPLIER_LIST = 'master.supplier.list';

    public const MASTER_SUPPLIER_CREATE = 'master.supplier.create';

    public const MASTER_SUPPLIER_EDIT = 'master.supplier.edit';

    public const MASTER_UNIT_LIST = 'master.unit.list';

    public const MASTER_UNIT_CREATE = 'master.unit.create';

    public const MASTER_UNIT_EDIT = 'master.unit.edit';

    /**
     * List user permissions for data stock
     */
    public const STOCK_LIST = 'stock.list';

    public const STOCK_MOVEMENT_LIST = 'stockmovement.list';
}
