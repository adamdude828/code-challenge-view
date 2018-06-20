<?php
/**
 * Created by PhpStorm.
 * User: aholsinger
 * Date: 6/18/18
 * Time: 10:38 PM
 */

namespace App\Http\Controllers;

use App\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{

    public function index() {
        $formattedTree = Node::getAllAsTree();
        return response()->json($formattedTree);
    }


    public function create(Request $request) {
        $data = $request->all();
        Node::insertNewIntoTree($data);
        return response('', 201);
    }

    public function delete($node_id) {
        $node = Node::find($node_id);
        if (is_null($node)) {
            return response("Not Found", 404);
        }

        $node->delete();
        return response("", 200);
    }

    public function edit($node_id, Request $request) {
        $node = Node::find($node_id);
        if (is_null($node)) {
            return response("Not Found", 404);
        }

        $node->updateInTree($request->input('supervisor_id'));

        return response("", 200);
    }


    public function view() {
        $nodes = Node::query()
                        ->orderBy('lft', 'asc')
                        ->get();

        return View('tree', ['nodes'=>$nodes]);
    }



}