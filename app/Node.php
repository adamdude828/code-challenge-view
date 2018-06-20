<?php
/**
 * Created by PhpStorm.
 * User: aholsinger
 * Date: 6/18/18
 * Time: 10:27 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Node extends Model
{
    protected $table = 'node';

    protected $fillable = [
        'name',
        'lft',
        'rgt',
        'level'
    ];

    public $timestamps = false;

    public static function getAllAsTree(): Array
    {

        $rootNode = self::query()
            ->orderBy('lft', 'asc')
            ->get();

        $result = [];
        foreach ($rootNode as $node) {
            $nodeData = $node->toArray();
            $nodeData['children'] = self::getChildNodes($node);
            $result[] = $nodeData;
        }

        return $result;
    }

    public static function getChildNodes($rootNode)
    {

        //SELECT * FROM node AS node, node AS parent WHERE node.lft > parent.lft AND node.rgt < parent.rgt AND parent.name = 'William Riker' ORDER BY node.lft
        $builder = self::query()
            ->select(['node.name', 'node.id'])
            ->join('node as parent', 'node.id', '=', 'node.id')
            ->whereRaw('node.lft > parent.lft')
            ->whereRaw('node.rgt < parent.rgt')
            ->where('parent.name', $rootNode->name);
        return $builder->get()->pluck('name');
    }

    public static function insertNewIntoTree($data) {
        $node = new self();
        $node->name = $data['name'];
        self::insertIntoTree($node, $data['supervisor_id']);
    }

    public static function insertIntoTree($node, $supervisor_id)
    {
        //get the parent
        $parent = self::find($supervisor_id);

        self::where("rgt", ">=", $parent->rgt)
            ->update(['rgt'=> DB::Raw('rgt + 2')]);

        self::where("lft", ">", $parent->rgt)
            ->update(['lft' => DB::Raw('lft + 2')]);

        $node->lft = $parent->rgt;
        $node->rgt = $parent->rgt + 1;
        $node->level = $parent->level + 1;
        $node->save();
    }

    public function delete() {

        $this->deleteFromTree();
        parent::delete();
    }

    public function deleteFromTree() {

        self::where("rgt", ">", $this->rgt)
            ->update(['rgt'=> DB::Raw('rgt - 2')]);

        self::where("lft", ">", $this->rgt)
            ->update(['lft' => DB::Raw('lft - 2')]);
    }

    public function updateInTree($supervisor_id) {
         $this->deleteFromTree();
         self::insertIntoTree($this, $supervisor_id);
    }

}