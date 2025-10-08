<?php


return [
    'channels' => [
        'navigation_label' => '渠道',
        'plural_label' => '渠道列表',
        'fields' => [
            'id' => 'ID',
            'name' => '渠道名称',
            'secret_key' => '密钥',
            'status' => '状态',
            'created_at' => '创建时间',
        ],
        'actions' => [
            'edit' => '编辑',
            'delete' => '删除',
            'refresh_key' => '生成新密钥',
        ],
        'filters' => [
            'status' => '是否激活？',
            'active' => '激活',
            'inactive' => '未激活',
        ],
    ],
    'channel_stats' => [
        'navigation_label' => '渠道统计',
        'plural_label' => '渠道统计列表',
        'fields' => [
            'id' => 'ID',
            'channel' => '渠道',
            'stat_date' => '日期',
            'visit_count' => '访问量',
            'ip_count' => '唯一IP',
            'recharge_amount' => '充值总额',
            'success_recharge_amount' => '充值成功',
            'register_count' => '注册量',
            'created_at' => '创建时间',
        ],
    ],
    'recharge_logs' => [
        'navigation_label' => '充值记录',
        'plural_label' => '充值记录列表',
        'fields' => [
            'id' => 'ID',
            'channel' => '渠道',
            'amount' => '金额',
            'status' => '状态',
            'created_at' => '创建时间',
        ],
        'status_labels' => [
            'pending' => '待处理',
            'success' => '成功',
            'failed' => '失败',
        ],
    ],
];
