<?php
return [
    'title'  => '分类',
    'single' => '分类',
    'model'  => \App\Models\Category::class,

    'action_permissions' => [
        'delete' => function () {
            return Auth::user()->hasRole('Founder');
        },
    ],

    'columns' => [
        'id'          => [
            'title' => 'ID',
        ],
        'name'        => [
            'title'    => '名称',
            'sortable' => false,
        ],
        'description' => [
            'title'    => '描述',
            'sortable' => false,
        ],
        'operation'   => [
            'title'    => '管理',
            'sortable' => false,
        ],
    ],

    'edit_fields' => [
        'name'        => [
            'title' => '名称',
        ],
        'description' => [
            'title' => '描述',
            'type'  => 'textarea',
        ],
    ],

    'filters' => [
        'name'        => [
            'title' => '名称'
        ],
        'description' => [
            'title' => '描述',
        ]
    ],

    'rule' => [
        'name' => 'required|min:1|unique:categories.name'
    ],

    'messages' => [
        'name.unique'   => '分类名已经存在',
        'name.required' => '分类名不得为空',
    ]


];
