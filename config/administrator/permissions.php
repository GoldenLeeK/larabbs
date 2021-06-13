<?php
return [
    'title'  => '权限',
    'single' => '权限',
    'model'  => \Spatie\Permission\Models\Permission::class,

    'permission'        => function () {
        return Auth::user()->can('manage_users');
    },

    //CURD单独对权限进行判断
    'action_permissions' => [
        'delete' => function () {
            return false;//权限不允许删除,其他不设置默认通过
        },
    ],

    'columns' => [
        'id'        => [
            'title' => 'ID',
        ],
        'name'      => [
            'title' => '标识',
        ],
        'operation' => [
            'title'    => '管理',
            'sortable' => 'false',
        ],
    ],

    'edit_fields' => [
        'name'  => [
            'title' => '标识(谨慎修改)',
            'hint'  => '修改权限标识会影响代码调用,请不要轻易修改',
        ],
        'roles' => [
            'type'       => 'relationship',
            'title'      => '角色',
            'name_field' => 'name',
        ]
    ],

    'filters' => [
        'name' => [
            'title' => '标识',
        ]
    ],

    'rule'     => [
        'name' => 'required',
    ],

    'messages' => [
        'name.required' => '标识不得为空',
    ]
];
