<?php

return [
    
    /**
     * Degree of the balance rounding for displaying.
     * Important! Can not be more than 8, because this number if decimals is set for DB field, so you can just decrease it
     * If you want to increase it, change the schema of the DB table wallet_infos
     */
    'rounding_degree' => 8,
    
    /**
     * The number of wallets per page at the address list
     */
    'wallets_per_page' => 20
];

