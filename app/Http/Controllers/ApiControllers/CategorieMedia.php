<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentInstructeurCategorie;
use App\Models\PhotoInstructeurCategorie;
use App\Models\VideoInstructeurCategorie;
use App\Models\UserPhotos;
use App\Models\UserVideos;
use App\Models\UserDocuments;
use Illuminate\Http\Request;

class CategorieMedia extends Controller
{
    public function index_cat_photos()
    {
        $list_cat = PhotoInstructeurCategorie::all();

        return response()->json([
            'status'   => true,
            'list_cat' => $list_cat,
            'message'  => '',
        ]);
    }

    public function add_cat_photos(Request $request)
    {
        $max_id = PhotoInstructeurCategorie::max('id') + 1;
        $code   = 'CPI_00' . $max_id;
        PhotoInstructeurCategorie::create([
            'code' => $code,
            'desc' => $request->desc,
        ]);

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    public function edit_cat_photos($id)
    {
        $detail = PhotoInstructeurCategorie::where('id', $id)->get();

        return response()->json([
            'status'  => true,
            'detail'  => $detail,
            'message' => '',
        ]);
    }

    public function update_cat_photos($id, Request $request)
    {
        PhotoInstructeurCategorie::where('id', $id)->update([
            'desc' => $request->desc,
        ]);

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    public function delete_cat_photos(Request $request)
    {
        $id = $request->champ_id;

        // Supprimer les photos liées à cette catégorie avant de supprimer la catégorie
        UserPhotos::where('categ_id', $id)->delete();

        PhotoInstructeurCategorie::where('id', $id)->delete();

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    // =========================================================================

    public function index_cat_videos()
    {
        $list_cat = VideoInstructeurCategorie::all();

        return response()->json([
            'status'   => true,
            'list_cat' => $list_cat,
            'message'  => '',
        ]);
    }

    public function add_cat_videos(Request $request)
    {
        $max_id = VideoInstructeurCategorie::max('id') + 1;
        $code   = 'CVI_00' . $max_id;
        VideoInstructeurCategorie::create([
            'code' => $code,
            'desc' => $request->desc,
        ]);

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    public function edit_cat_videos($id)
    {
        $detail = VideoInstructeurCategorie::where('id', $id)->get();

        return response()->json([
            'status'  => true,
            'detail'  => $detail,
            'message' => '',
        ]);
    }

    public function update_cat_videos($id, Request $request)
    {
        VideoInstructeurCategorie::where('id', $id)->update([
            'desc' => $request->desc,
        ]);

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    public function delete_cat_videos(Request $request)
    {
        $id = $request->champ_id;

        // Supprimer les vidéos liées à cette catégorie avant de supprimer la catégorie
        UserVideos::where('categ_id', $id)->delete();

        VideoInstructeurCategorie::where('id', $id)->delete();

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    // =========================================================================

    public function index_cat_documents()
    {
        $list_cat = DocumentInstructeurCategorie::all();

        return response()->json([
            'status'   => true,
            'list_cat' => $list_cat,
            'message'  => '',
        ]);
    }

    public function add_cat_documents(Request $request)
    {
        $max_id = DocumentInstructeurCategorie::max('id') + 1;
        $code   = 'CVI_00' . $max_id;
        DocumentInstructeurCategorie::create([
            'code' => $code,
            'desc' => $request->desc,
        ]);

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    public function edit_cat_documents($id)
    {
        $detail = DocumentInstructeurCategorie::where('id', $id)->get();

        return response()->json([
            'status'  => true,
            'detail'  => $detail,
            'message' => '',
        ]);
    }

    public function update_cat_documents($id, Request $request)
    {
        DocumentInstructeurCategorie::where('id', $id)->update([
            'desc' => $request->desc,
        ]);

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }

    public function delete_cat_documents(Request $request)
    {
        $id = $request->champ_id;

        // Supprimer les documents liés à cette catégorie avant de supprimer la catégorie
        UserDocuments::where('categ_id', $id)->delete();

        DocumentInstructeurCategorie::where('id', $id)->delete();

        return response()->json([
            'status'  => true,
            'message' => '',
        ]);
    }
}
