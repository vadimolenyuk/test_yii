<?php
namespace app\models;

class AlbumDetails extends Album
{
    public function fields()
    {
        return [
            'id',
            'title',
            'first_name' => function($model) {
                return $model->user ? $model->user->firstname : null;
            },
            'last_name' => function($model) {
                return $model->user ? $model->user->lastname : null;
            },
            'photos',
        ];
    }
}
