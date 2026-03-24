<?php

namespace backend\tests\fixtures;

use yii\test\ActiveFixture;

class AlbumFixture extends ActiveFixture
{
    public $modelClass = 'backend\models\Album';
    public $depends = ['backend\tests\fixtures\UserFixture'];
    public $dataFile = '@backend/tests/fixtures/data/album.php';
}