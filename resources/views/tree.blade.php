@extends('layouts.app')
@foreach($nodes as $key => $node)
    <div class="node level-{{$node->level + 1}}" newlevel="{[$newLevel}}">
        <div class="name">{{$node->name}}</div>
    </div>
@endforeach