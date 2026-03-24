<?php

namespace backend\tests\fixtures;

use yii\test\ActiveFixture;

class PhotoFixture extends ActiveFixture
{
    public $modelClass = 'backend\models\Photo';
    public $depends = ['backend\tests\fixtures\AlbumFixture'];
    public $dataFile = '@backend/tests/fixtures/data/photo.php';
}