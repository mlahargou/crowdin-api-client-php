<?php

namespace CrowdinApiClient\Tests\Api;

use CrowdinApiClient\Model\SourceString;
use CrowdinApiClient\ModelCollection;

class SourceStringApiTest extends AbstractTestApi
{
    public function testList()
    {
        $this->mockRequest([
            'path' => '/projects/2/strings',
            'method' => 'get',
            'response' => '{
              "data": [
                {
                  "data": {
                    "id": 2814,
                    "projectId": 2,
                    "fileId": 48,
                    "branchId": 12,
                    "identifier": "6a1821e6499ebae94de4b880fd93b985",
                    "text": "Not all videos are shown to users. See more",
                    "type": "text",
                    "context": "shown on main page",
                    "maxLength": 35,
                    "isHidden": false,
                    "revision": 1,
                    "hasPlurals": false,
                    "isIcu": false,
                    "createdAt": "2019-09-20T12:43:57+00:00",
                    "updatedAt": "2019-09-20T13:24:01+00:00"
                  }
                }
              ],
              "pagination": [
                {
                  "offset": 0,
                  "limit": 0
                }
              ]
            }'
        ]);

        $sourceStrings = $this->crowdin->sourceString->list(2);

        $this->assertInstanceOf(ModelCollection::class, $sourceStrings);
        $this->assertCount(1, $sourceStrings);
        $this->assertInstanceOf(SourceString::class, $sourceStrings[0]);
        $this->assertEquals(2814, $sourceStrings[0]->getId());
        $this->assertEquals(12, $sourceStrings[0]->getBranchId());
    }

    public function testGetAndUpdate()
    {
        $this->mockRequestGet('/projects/2/strings/2814', '{
          "data": {
            "id": 2814,
            "projectId": 2,
            "fileId": 48,
            "branchId": 12,
            "identifier": "6a1821e6499ebae94de4b880fd93b985",
            "text": "Not all videos are shown to users. See more",
            "type": "text",
            "context": "shown on main page",
            "maxLength": 35,
            "isHidden": false,
            "revision": 1,
            "hasPlurals": false,
            "isIcu": false,
            "createdAt": "2019-09-20T12:43:57+00:00",
            "updatedAt": "2019-09-20T13:24:01+00:00"
          }
        }');

        $sourceString = $this->crowdin->sourceString->get(2, 2814);
        $this->assertInstanceOf(SourceString::class, $sourceString);
        $this->assertEquals(2814, $sourceString->getId());

        $this->mockRequestPatch('/projects/2/strings/2814', '{
                  "data": {
                    "id": 2814,
                    "projectId": 2,
                    "fileId": 48,
                    "branchId": 12,
                    "identifier": "6a1821e6499ebae94de4b880fd93b985",
                    "text": "test edit",
                    "type": "text",
                    "context": "shown on main page",
                    "maxLength": 35,
                    "isHidden": false,
                    "revision": 1,
                    "hasPlurals": false,
                    "isIcu": false,
                    "createdAt": "2019-09-20T12:43:57+00:00",
                    "updatedAt": "2019-09-20T13:24:01+00:00",
                    "isDuplicate": false,
                  }
                }'
        );

        $sourceString->setText('test edit');
        $this->crowdin->sourceString->update($sourceString);
        $this->assertInstanceOf(SourceString::class, $sourceString);
        $this->assertEquals(2814, $sourceString->getId());
        $this->assertEquals('test edit', $sourceString->getText());
        $this->assertEquals(12, $sourceString->getBranchId());
    }

    public function testCreate()
    {
        $params = [
            'identifier' => 'identifier',
            'text' => 'Not all videos are shown to users. See more'
        ];

        $this->mockRequest([
            'path' => '/projects/2/strings',
            'method' => 'post',
            'response' => '{
                  "data": {
                    "id": 2814,
                    "projectId": 2,
                    "fileId": 48,
                    "branchId": 12,
                    "identifier": "6a1821e6499ebae94de4b880fd93b985",
                    "text": "Not all videos are shown to users. See more",
                    "type": "text",
                    "context": "shown on main page",
                    "maxLength": 35,
                    "isHidden": false,
                    "revision": 1,
                    "hasPlurals": false,
                    "isIcu": false,
                    "createdAt": "2019-09-20T12:43:57+00:00",
                    "updatedAt": "2019-09-20T13:24:01+00:00"
                  }
                }',
            'body' => json_encode($params)
        ]);

        $sourceString = $this->crowdin->sourceString->create(2, $params);

        $this->assertInstanceOf(SourceString::class, $sourceString);
        $this->assertEquals(2814, $sourceString->getId());
    }

    public function testDelete()
    {
        $this->mockRequest([
            'path' => '/projects/2/strings/2814',
            'method' => 'delete',
        ]);

        $this->crowdin->sourceString->delete(2, 2814);
    }

    public function testBatch()
    {
        $this->mockRequest([
            'path' => '/projects/2/strings',
            'method' => 'patch',
            'response' => '{
              "data":[
                {
                  "data":{
                    "id":2814,
                    "projectId": 2,
                    "branchId":null,
                    "identifier":"a.b.c",
                    "text":"new added string",
                    "type":"text",
                    "context":"a.b.c\ncontext for new string",
                    "maxLength":0,
                    "isHidden":false,
                    "isDuplicate":false,
                    "masterStringId":null,
                    "hasPlurals":false,
                    "isIcu":false,
                    "labelIds":[],
                    "webUrl":"https://example.crowdin.com/editor/1/all/en-pl?filter=basic&value=0&view=comfortable#2",
                    "createdAt":"2024-11-13T16:56:18+00:00",
                    "updatedAt":null,
                    "fileId":48,
                    "directoryId":null,
                    "revision":1
                  }
                }
              ]
            }'
          ]);

        $batchResult = $this->crowdin->sourceString->batchOperations(2, [
            [
              'op' => 'replace',
              'path' => '/2814/isHidden',
              'value' => true
            ],
            [
              'op' => 'replace',
              'path' => '/2814/context',
              'value' => 'some context'
            ],
            [
              'op' => 'add',
              'path' => '/-',
              'value' => [
                'text' => 'new added string',
                'identifier' => 'a.b.c',
                'context' => 'context for new string',
                'fileId' => 8,
                'isHidden' => false
              ]
             ],
            [
              'op' => 'remove',
              'path' => '/2814'
            ]
          ]);

        $this->assertInstanceOf(SourceString::class, $batchResult);
    }
}
