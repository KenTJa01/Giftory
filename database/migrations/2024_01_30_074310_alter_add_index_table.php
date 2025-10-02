<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /** USER SITES */
        if (Schema::hasColumn('user_sites', 'site_id') && Schema::hasColumn('user_sites', 'user_id')) {
            Schema::table('user_sites', function (Blueprint $table) {
                $table->index(['site_id', 'user_id']);
            });
        }

        /** PROFILE MENU */
        if (Schema::hasColumn('profile_menus', 'profile_id') && Schema::hasColumn('profile_menus', 'sub_menu_id')) {
            Schema::table('profile_menus', function (Blueprint $table) {
                $table->index(['profile_id', 'sub_menu_id']);
            });
        }

        /** USERS */
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('username');
            });
        }

        /** PERMISSIONS */
        if (Schema::hasColumn('permissions', 'key')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->index('key');
            });
        }
        if (Schema::hasColumn('permissions', 'sub_menu_id')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->index('sub_menu_id');
            });
        }

        /** PROFILE PERMISSIONS */
        if (Schema::hasColumn('profile_permissions', 'profile_id') && Schema::hasColumn('profile_permissions', 'permission_id')) {
            Schema::table('profile_permissions', function (Blueprint $table) {
                $table->index(['profile_id', 'permission_id']);
            });
        }

        /** PROFILE LOCATION */
        if (Schema::hasColumn('profile_locations', 'profile_id') && Schema::hasColumn('profile_locations', 'location_id')) {
            Schema::table('profile_locations', function (Blueprint $table) {
                $table->index(['profile_id', 'location_id']);
            });
        }

        /** STOCKS */
        if (Schema::hasColumn('stocks', 'site_id') && Schema::hasColumn('stocks', 'location_id') && Schema::hasColumn('stocks', 'catg_id')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->index(['site_id', 'location_id', 'catg_id']);
            });
        }
        if (Schema::hasColumn('stocks', 'so_flag')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->index('so_flag');
            });
        }

        /** STOCK MOVEMENTS */
        if (Schema::hasColumn('stock_movements', 'site_id')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->index('site_id');
            });
        }
        if (Schema::hasColumn('stock_movements', 'location_id')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->index('location_id');
            });
        }
        if (Schema::hasColumn('stock_movements', 'catg_id')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->index('catg_id');
            });
        }
        if (Schema::hasColumn('stock_movements', 'mov_code')) {
            Schema::table('stock_movements', function (Blueprint $table) {
                $table->index('mov_code');
            });
        }

        /** RECEIVING HEADERS */
        if (Schema::hasColumn('receiving_headers', 'rec_no')) {
            Schema::table('receiving_headers', function (Blueprint $table) {
                $table->index('rec_no');
            });
        }

        /** RECEIVING DETAILS */
        if (Schema::hasColumn('receiving_details', 'rec_id')) {
            Schema::table('receiving_details', function (Blueprint $table) {
                $table->index('rec_id');
            });
        }

        /** TRANSFER HEADERS */
        if (Schema::hasColumn('transfer_headers', 'trf_no')) {
            Schema::table('transfer_headers', function (Blueprint $table) {
                $table->index('trf_no');
            });
        }
        if (Schema::hasColumn('transfer_headers', 'origin_site_id') && Schema::hasColumn('transfer_headers', 'flag')) {
            Schema::table('transfer_headers', function (Blueprint $table) {
                $table->index(['origin_site_id', 'flag']);
            });
        }

        /** TRANSFER DETAILS */
        if (Schema::hasColumn('transfer_details', 'trf_id')) {
            Schema::table('transfer_details', function (Blueprint $table) {
                $table->index('trf_id');
            });
        }

        /** EXPENDING HEADERS */
        if (Schema::hasColumn('expending_headers', 'req_no')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->index('req_no');
            });
        }
        if (Schema::hasColumn('expending_headers', 'origin_site_id') && Schema::hasColumn('expending_headers', 'location_id') && Schema::hasColumn('expending_headers', 'flag')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->index(['origin_site_id', 'location_id', 'flag']);
            });
        }

        /** EXPENDING DETAILS */
        if (Schema::hasColumn('expending_details', 'req_id')) {
            Schema::table('expending_details', function (Blueprint $table) {
                $table->index('req_id');
            });
        }

        /** STOCK OPNAME HEADERS */
        if (Schema::hasColumn('stock_opname_headers', 'so_no')) {
            Schema::table('stock_opname_headers', function (Blueprint $table) {
                $table->index('so_no');
            });
        }
        if (Schema::hasColumn('stock_opname_headers', 'site_id') && Schema::hasColumn('stock_opname_headers', 'location_id') && Schema::hasColumn('stock_opname_headers', 'flag')) {
            Schema::table('stock_opname_headers', function (Blueprint $table) {
                $table->index(['site_id', 'location_id', 'flag']);
            });
        }

        /** STOCK OPNAME DETAILS */
        if (Schema::hasColumn('stock_opname_details', 'so_id')) {
            Schema::table('stock_opname_details', function (Blueprint $table) {
                $table->index('so_id');
            });
        }

        /** STOCK BOOKINGS */
        if (Schema::hasColumn('stock_bookings', 'site_id') && Schema::hasColumn('stock_bookings', 'location_id') && Schema::hasColumn('stock_bookings', 'catg_id')) {
            Schema::table('stock_bookings', function (Blueprint $table) {
                $table->index(['site_id', 'location_id', 'catg_id']);
            });
        }
        if (Schema::hasColumn('stock_bookings', 'book_type') && Schema::hasColumn('stock_bookings', 'reference_no')) {
            Schema::table('stock_bookings', function (Blueprint $table) {
                $table->index(['book_type', 'reference_no']);
            });
        }

        /** ADJUSTMENT HEADERS */
        if (Schema::hasColumn('adjustment_headers', 'adj_no')) {
            Schema::table('adjustment_headers', function (Blueprint $table) {
                $table->index('adj_no');
            });
        }

        /** ADJUSTMENT DETAILS */
        if (Schema::hasColumn('adjustment_details', 'adj_id')) {
            Schema::table('adjustment_details', function (Blueprint $table) {
                $table->index('adj_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /** MASTER */
        Schema::table('user_sites', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'user_id']);
        });
        Schema::table('profile_menus', function (Blueprint $table) {
            $table->dropIndex(['profile_id', 'sub_menu_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username']);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['key']);
            $table->dropIndex(['sub_menu_id']);
        });
        Schema::table('profile_permissions', function (Blueprint $table) {
            $table->dropIndex(['profile_id', 'permission_id']);
        });
        Schema::table('profile_locations', function (Blueprint $table) {
            $table->dropIndex(['profile_id', 'location_id']);
        });

        /** TRANSACTION */
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'location_id', 'catg_id']);
            $table->dropIndex(['so_flag']);
        });
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['site_id']);
            $table->dropIndex(['location_id']);
            $table->dropIndex(['catg_id']);
            $table->dropIndex(['mov_code']);
        });
        Schema::table('receiving_headers', function (Blueprint $table) {
            $table->dropIndex(['rec_no']);
        });
        Schema::table('receiving_details', function (Blueprint $table) {
            $table->dropIndex(['rec_id']);
        });
        Schema::table('transfer_headers', function (Blueprint $table) {
            $table->dropIndex(['trf_no']);
            $table->dropIndex(['origin_site_id', 'flag']);
        });
        Schema::table('transfer_details', function (Blueprint $table) {
            $table->dropIndex(['trf_id']);
        });
        Schema::table('expending_headers', function (Blueprint $table) {
            $table->dropIndex(['req_no']);
            $table->dropIndex(['origin_site_id', 'location_id', 'flag']);
        });
        Schema::table('expending_details', function (Blueprint $table) {
            $table->dropIndex(['req_id']);
        });
        Schema::table('stock_opname_headers', function (Blueprint $table) {
            $table->dropIndex(['so_no']);
            $table->dropIndex(['site_id', 'location_id', 'flag']);
        });
        Schema::table('stock_opname_details', function (Blueprint $table) {
            $table->dropIndex(['so_id']);
        });
        Schema::table('stock_bookings', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'location_id', 'catg_id']);
            $table->dropIndex(['book_type', 'reference_no']);
        });
        Schema::table('adjustment_headers', function (Blueprint $table) {
            $table->dropIndex(['adj_no']);
        });
        Schema::table('adjustment_details', function (Blueprint $table) {
            $table->dropIndex(['adj_id']);
        });
    }
};
