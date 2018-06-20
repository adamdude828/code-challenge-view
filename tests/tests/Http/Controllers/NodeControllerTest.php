<?php
/**
 * Created by PhpStorm.
 * User: aholsinger
 * Date: 6/18/18
 * Time: 10:45 PM
 */

namespace AppTest\tests\Http\Controllers;


use App\Node;
use Tests\TestCase;

class NodeControllerTest extends TestCase
{

   // use DatabaseTransactions;

    public function testGetNodes()
    {
        $result = $this->get("/nodes")
            ->baseResponse->content();

        $nodes = json_decode($result);

        //make sure the nodes returned match the expected count
        $expectedCount = Node::count();
        $this->assertEquals($expectedCount, count($nodes));
    }

    public function testCreateNode()
    {

        //get the id of Jean-luc
        $captain = Node::where('name', 'Jean-Luc Picard')
            ->get()->first();


        $this->post("/nodes",
            [
                'name' => 'Captain Adam',
                'supervisor_id' => $captain->id
            ]);

        $children = Node::getChildNodes($captain);
        $this->assertContains('Captain Adam', $children);


        $adam = Node::where("name", "Captain Adam")->get()->first();
        $this->post("/nodes",
            [
                'name' => 'lt Melissa',
                'supervisor_id' => $adam->id
            ]);
        $children = Node::getChildNodes($adam);
        $this->assertContains('lt Melissa', $children);


        $melissa = Node::where("name", "lt Melissa")->get()->first();
        $this->post("/nodes",
            [
                'name' => 'Ensign Jacob',
                'supervisor_id' => $melissa->id
            ]);
        $children = Node::getChildNodes($melissa);
        $this->assertContains('Ensign Jacob', $children);
    }

    public function testDeleteNode() {
        $captain = Node::where('name', 'Jean-Luc Picard')
            ->get()->first();
        Node::insertNewIntoTree([
            'name' => 'Captain Adam',
            'supervisor_id'=>$captain->id
        ]);

        $adam = Node::where("name", "Captain Adam")->get()->first();

        $this->delete("/nodes/" . $adam->id);

        $children = Node::getChildNodes($captain);
        $this->assertNotContains('Captain Adam', $children);
    }


    public function testEditNode() {
        $deanna = Node::where("name", "Deanna Troi")->get()->first();
        $newCapo = Node::where("name", "William Riker")->get()->first();

        $this->put("/nodes/" . $deanna->id, [
            'supervisor_id'=>$newCapo->id
        ]);

        $children = Node::getChildNodes($newCapo);
        $this->assertContains('Deanna Troi', $children);
    }




}