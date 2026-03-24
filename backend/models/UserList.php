<?php

namespace backend\models;

class UserList extends User
{
    public function fields()
    {
        return [
            'id',
            'first_name' => 'firstname',
            'last_name' => 'lastname',
        ];
    }
}
