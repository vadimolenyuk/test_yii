<?php

namespace backend\controllers;

use backend\models\AlbumDetails;
use backend\models\AlbumList;
use backend\models\User;
use backend\models\UserList;
use Yii;
use yii\web\Response;
use yii\web\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

#[OA\Info(title:"PHP BE Dev test task (YII2)", version: "1.0.0"), Contact('Olenyuk Vadym')]
class SiteController extends Controller
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    private function pagination($query,$page, $perPage)
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $perPage,
                'page' => $page - 1,
            ],
        ]);

        return [
            'items' => $provider->getModels(),
            'total' => $provider->getTotalCount(),
            'page' => $page,
            'perPage' => $perPage,
        ];
    }

    #[OA\Get(
        path: "/users",
        summary: "Get all users (paginated)",
        parameters: [
            new OA\Parameter(
                name: "page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", default: 1)
            ),
            new OA\Parameter(
                name: "perPage",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", default: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            )
        ]
    )]
    public function actionUsers($page = 1, $perPage = 10)
    {
        return $this->pagination(
            UserList::find(),
            $page,
            $perPage
        );
    }

    #[OA\Get(
        path: "/users/{id}",
        summary: "Get user by ID",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            ),
            new OA\Response(
                response: 404,
                description: "User not found"
            )
        ]
    )]
    public function actionUser($id)
    {
        $user = User::find()
            ->with('albums.photos')
            ->where(['id' => $id])
            ->one();

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

       return $user->toArray([], ['albums', 'albums.photos']);
    }

    #[OA\Get(
        path: "/albums",
        summary: "Get all albums (paginated)",
        parameters: [
            new OA\Parameter(
                name: "page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", default: 1)
            ),
            new OA\Parameter(
                name: "perPage",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", default: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            )
        ]
    )]
    public function actionAlbums($page = 1, $perPage = 10)
    {
        return $this->pagination(
            AlbumList::find(),
            $page,
            $perPage
        );
    }

    #[OA\Get(
        path: "/albums/{id}",
        summary: "Get album by ID",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            ),
            new OA\Response(
                response: 404,
                description: "Album not found"
            )
        ]
    )]
    public function actionAlbum($id)
    {
        $album = AlbumDetails::find()
            ->with('photos', 'user')
            ->where(['id' => $id])
            ->one();

        if (!$album) {
            throw new NotFoundHttpException('Album not found');
        }

       return $album;
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }
}