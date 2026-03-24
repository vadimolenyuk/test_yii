<?php
namespace backend\models;

class AlbumList extends Album
{
    public function fields()
    {
        return [
            'id',
            'title',
        ];
    }
}
