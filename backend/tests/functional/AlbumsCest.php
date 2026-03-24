<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use backend\tests\fixtures\UserFixture;
use backend\tests\fixtures\AlbumFixture;

class AlbumsCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => '@backend/tests/fixtures/data/user.php'
            ],
            'album' => [
                'class' => AlbumFixture::class,
                'dataFile' => '@backend/tests/fixtures/data/album.php'
            ],
        ]);
    }

    public function testGetAlbums(FunctionalTester $I)
    {
        $I->sendGET('/albums');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        
        $response = json_decode($I->grabResponse(), true);
        $I->assertArrayHasKey('items', $response);
        $I->assertArrayHasKey('total', $response);
        $I->assertArrayHasKey('page', $response);
        $I->assertArrayHasKey('perPage', $response);
        
        $I->assertEquals(5, $response['total']);
        $I->assertEquals(5, count($response['items']));
        
        $album = $response['items'][0];
        $I->assertArrayHasKey('id', $album);
        $I->assertArrayHasKey('title', $album);
    }


    public function testGetAlbumsPagination(FunctionalTester $I)
    {
        $I->sendGET('/albums', ['page' => 1, 'perPage' => 2]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        
        $response = json_decode($I->grabResponse(), true);
        
        $I->assertEquals(5, $response['total']);
        $I->assertEquals(1, $response['page']);
        $I->assertEquals(2, $response['perPage']);
        $I->assertEquals(2, count($response['items']));
    }

    public function testGetAlbumsPage(FunctionalTester $I)
    {
        $I->sendGET('/albums', ['page' => -1]);
        $I->seeResponseCodeIs(200);
        
        $response = json_decode($I->grabResponse(), true);
    
        $I->assertEquals(1, $response['page']);
        $I->assertEquals(5, $response['total']);
    }

    public function testAlbumStructure(FunctionalTester $I)
    {
        $I->sendGET('/albums', ['perPage' => 1]);
        $I->seeResponseCodeIs(200);
        
        $response = json_decode($I->grabResponse(), true);
        $album = $response['items'][0];
        
        $I->assertArrayHasKey('id', $album);
        $I->assertArrayHasKey('title', $album);
        
        $I->assertIsInt($album['id']);
        $I->assertIsString($album['title']);
    }
}