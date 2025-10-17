<?php


return [
    'brand_name' => '统计后台',
    'users' => [
        'navigation_label' => '用户管理',
        'plural_label' => '用户列表',
        'fields' => [
            'id' => 'ID',
            'name' => '姓名',
            'email' => '邮箱',
            'password' => '密码',
            'created_at' => '创建时间',
            'role' => '角色',
        ],
        'actions' => [
            'edit' => '编辑',
            'delete' => '删除',
            'attach_channel' => '关联频道',
        ],
        'placeholders' => [
            'select_channel' => '请选择或搜索可用频道',
        ],
        'filters' => [
        ],
    ],
    'navigation' => [
        'statistic' => '统计',
        'setting' => '设置',
    ],
    'channels' => [
        'navigation_label' => '渠道',
        'plural_label' => '渠道列表',
        'fields' => [
            'id' => 'ID',
            'channel_code' => '渠道编码',
            'name' => '渠道名称',
            'secret_key' => '密钥',
            'status' => '状态',
            'created_at' => '创建时间',
        ],
        'actions' => [
            'edit' => '编辑',
            'delete' => '删除',
            'refresh_key' => '生成新密钥',
            'create' => '新增渠道',
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
            'recharge_count' => '充值次数',
            'success_recharge_count' => '成功充值次数',
            'login_count' => '登录次数',
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
    'roles' => [
        'super_admin' => '超级管理员',
        'admin' => '管理员',
        'user' => '普通用户',
    ],
];
