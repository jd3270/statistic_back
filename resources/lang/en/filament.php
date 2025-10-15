<?php

return [
    'brand_name' => 'Statistic Panel',
    'users' => [
        'brand_name' => 'Statistics Panel',
        'navigation_label' => 'Users',
        'plural_label' => 'User List',
        'fields' => [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'created_at' => 'Created At',
            'role' => 'Role',
        ],
        'actions' => [
            'edit' => 'Edit',
            'delete' => 'Delete',
            'attach_channel' => 'Attach Channel',
        ],
        'filters' => [
            // Custom filters if needed
        ],
    ],
    'navigation' => [
        'statistic' => 'Statistic',
        'setting' => 'Setting',
    ],
    'channels' => [
        'navigation_label' => 'Channels',
        'plural_label' => 'Channels',
        'fields' => [
            'id' => 'ID',
            'channel_code' => 'Channel Code',
            'name' => 'Channel Name',
            'secret_key' => 'Secret Key',
            'status' => 'Status',
            'created_at' => 'Created At',
        ],
        'actions' => [
            'edit' => 'Edit',
            'delete' => 'Delete',
            'refresh_key' => 'Generate new key',
            'create' => 'New Channel',
        ],
        'filters' => [
            'status' => 'Active?',
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],
    ],
    'channel_stats' => [
        'navigation_label' => 'Channel Stats',
        'plural_label' => 'Channel Stats List',
        'fields' => [
            'id' => 'ID',
            'channel' => 'Channel',
            'stat_date' => 'Date',
            'visit_count' => 'Visits',
            'ip_count' => 'Unique IPs',
            'recharge_amount' => 'Recharge (Total)',
            'success_recharge_amount' => 'Recharge (Success)',
            'register_count' => 'Registrations',
            'created_at' => 'Created At',
            'recharge_count' => 'Recharge Count',
            'success_recharge_count' => 'Successful Recharge Count',
            'login_count' => 'Login Count',
        ],
    ],
    'recharge_logs' => [
        'navigation_label' => 'Recharge Logs',
        'plural_label' => 'Recharge Logs List',
        'fields' => [
            'id' => 'ID',
            'channel' => 'Channel',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
        ],
        'status_labels' => [
            'pending' => 'Pending',
            'success' => 'Success',
            'failed' => 'Failed',
        ],
    ],
    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'user' => 'User',
    ],
];
