<?php
return [
    'title'  => '资源推荐',
    'single' => '资源推荐',
    'model'  => \App\Models\Link::class,

    'permission' => function () {
        return Auth::user()->hasRole('Founder');
    },

    'columns'     => [
        'id'          => [
            'title' => 'ID',
        ],
        'title'       => [
            'title'    => 'title',
            'sortable' => false,
        ],
        'link'        => [
            'title'    => '链接',
            'output'   => function ($value, $model) {
                return '<a href="' . $value . '" target="_blank">' . $value . '</a>';
            },
            'sortable' => false,
        ],
        'thumb_image' => [
            'title'    => '缩略图',
            'output'   => function ($value, $model) {
                return empty($value) ? 'N\A' : '<img src ="' . $value . '" width="40">';
            },
            'sortable' => false,
        ],
        'operation'   => [
            'title'    => '管理',
            'sortable' => false,
        ]
    ],
    'edit_fields' => [
        'title'       => [
            'title' => '名称',
        ],
        'link'        => [
            'title' => '链接',
        ],
        'thumb_image' => [
            'title'    => '缩略图',
            'type'     => 'image',
            'location' => public_path() . '/uploads/images/links/'
        ],
    ],

    'filters' => [
        'id'          => [
            'title' => '标签 ID',
        ],
        'title'       => [
            'title' => '名称',
        ],
        'thumb_image' => [
            'title' => '缩略图',
        ],
    ],
];
