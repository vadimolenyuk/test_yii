<?php
namespace backend\tests\unit;

use backend\models\Album;
use backend\models\User;
use backend\tests\fixtures\AlbumFixture;
use backend\tests\fixtures\UserFixture;
use \Codeception\Test\Unit;

class AlbumTest extends Unit
{
    protected $tester;

    public function testSave()
    {

        $user = new \backend\models\User();
        $user->firstname = 'user1';
        $user->lastname = 'user1';
        $user->password_hash = "test";
        $user->auth_key = "test";
        $user->created_at = time();
        $user->updated_at = time();
        $user->save(false);

        $album = new Album();
        $album->user_id = 1;
        $album->title = 'Test';

        $this->assertTrue($album->save());
    }
}