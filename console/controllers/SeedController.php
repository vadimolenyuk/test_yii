<?php
namespace console\controllers;

use backend\models\Album;
use backend\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class SeedController extends Controller
{
    const MAX_ELEMENT = 10;
    private $​counter = 0;

    private function generationUsers()
    {        
        echo "Creating users...\n";
        $password = Yii::$app->params['demoPassword'];
        $users = [];
        while ($this->​counter < self::MAX_ELEMENT) {
            $this->​counter ++;
            $users[] = [
                'firstname' => "user_{$this->​counter}",
                'lastname' => "user_{$this->​counter}",
                'password_hash' => Yii::$app->security->generatePasswordHash($password),
                'auth_key' => Yii::$app->security->generateRandomString(32),
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        Yii::$app->db->createCommand()
        ->batchInsert('user', ['firstname', 'lastname', 'password_hash', 'auth_key', 'created_at', 'updated_at'], $users)
        ->execute();
        $this->​counter = 0;

        echo "Done!\n";
        return ExitCode::OK;
    }
    
    private function generationAlbums()
    {
        echo "Creating albums...\n";
        $albums = [];
        $users = User::find()->all();
        foreach($users as $user){
            while ($this->​counter < self::MAX_ELEMENT) {
                $this->​counter ++;
                $albums[] = [
                    'user_id' => $user->id,
                    'title' => "title_{$this->​counter}",
                ];
            }
            $this->​counter = 0;
        }

        Yii::$app->db->createCommand()
            ->batchInsert('album', ['user_id', 'title'], $albums)
            ->execute();
        echo "Done!\n";
        return ExitCode::OK;
    }

    private function generationPhotos()
    {
        echo "Creating photos...\n";
        $photos = [];
        $albums = Album::find()->all();
        foreach($albums as $album){
            while ($this->​counter < self::MAX_ELEMENT) {
                $this->​counter ++;
                $photos[] = [
                    'album_id' => $album->id,
                    'title' => "title_{$this->​counter}",
                    'url' =>  "images/{$this->​counter}.jpg",
                ];
            }
            $this->​counter = 0;
        }
        Yii::$app->db->createCommand()
            ->batchInsert('photo', ['album_id', 'title', 'url'], $photos)
            ->execute();
        echo "Done!\n";
        return ExitCode::OK;
    }
    
    public function actionIndex()
    {
        $this->generationUsers();
        $this->generationAlbums();
        $this->generationPhotos();
        
        echo "All seeders completed!\n";
        return ExitCode::OK;
    }
}