<?php
namespace backend\tests\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'backend\models\User';
    public $dataFile = '@backend/tests/fixtures/data/user.php';
}