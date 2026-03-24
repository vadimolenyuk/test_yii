<?php

namespace backend\models;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 *
 * @property Album[] $albums
 */

class User extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'required'],
            [['firstname'], 'string', 'max' => 255],
            [['lastname'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
        ];
    }

    /**
     * Gets query for [[Albums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Album::class, ['user_id' => 'id']);
    }

    public function fields()
    {
        return [
            'id',
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'albums'
        ];
    }

    public function extraFields()
    {
        return [
            'albums',
        ];
    }
}
