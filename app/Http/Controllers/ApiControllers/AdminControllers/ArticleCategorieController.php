<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategorie;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleCategorieController extends Controller
{
    public function index()
    {
        $liste = ArticleCategorie::orderBy('desc')->get();
        return response()->json(['status' => true, 'liste' => $liste, 'message' => '']);
    }

    public function add(Request $request)
    {
        $max_id = (ArticleCategorie::max('id') ?? 0) + 1;
        ArticleCategorie::create([
            'code' => 'ARC_' . str_pad($max_id, 3, '0', STR_PAD_LEFT),
            'desc' => $request->desc,
        ]);
        return response()->json(['status' => true, 'message' => '']);
    }

    public function edit($id)
    {
        $detail = ArticleCategorie::findOrFail($id);
        return response()->json(['status' => true, 'detail' => $detail, 'message' => '']);
    }

    public function update($id, Request $request)
    {
        ArticleCategorie::where('id', $id)->update(['desc' => $request->desc]);
        return response()->json(['status' => true, 'message' => '']);
    }

    public function delete(Request $request)
    {
        $id = $request->champ_id;
        // Désaffecter les articles liés
        Article::where('categ_id', $id)->update(['categ_id' => null]);
        ArticleCategorie::where('id', $id)->delete();
        return response()->json(['status' => true, 'message' => '']);
    }
}
