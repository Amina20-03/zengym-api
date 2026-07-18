<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    private function buildPhotoUrl(?string $path): ?string
    {
        if (!$path) return null;
        return 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/' . $path;
    }

    private function formatItem($item): array
    {
        return [
            'id'         => $item->id,
            'titre'      => $item->titre,
            'contenu'    => $item->contenu,
            'photo'      => $item->photo,
            'photo_url'  => $this->buildPhotoUrl($item->photo),
            'categ_id'   => $item->categ_id,
            'categ_desc' => $item->categ_id
                ? ArticleCategorie::where('id', $item->categ_id)->value('desc')
                : null,
            'ordre'      => $item->ordre,
            'active'     => $item->active,
            'created_at' => $item->created_at,
        ];
    }

    // -------------------------------------------------------------------------
    // Admin
    // -------------------------------------------------------------------------

    public function index()
    {
        $categories = ArticleCategorie::orderBy('desc')->get();

        $liste = Article::orderBy('categ_id')->orderBy('ordre')->orderBy('id', 'desc')
            ->get()->map(fn($i) => $this->formatItem($i));

        $grouped = [];
        foreach ($categories as $cat) {
            $grouped[] = [
                'categ_id'   => $cat->id,
                'categ_desc' => $cat->desc,
                'articles'   => $liste->where('categ_id', $cat->id)->values(),
            ];
        }
        $sans = $liste->whereNull('categ_id')->values();
        if ($sans->count() > 0) {
            $grouped[] = ['categ_id' => null, 'categ_desc' => 'Sans catégorie', 'articles' => $sans];
        }

        return response()->json([
            'status'     => true,
            'liste'      => $liste,
            'grouped'    => $grouped,
            'categories' => $categories,
            'message'    => '',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'    => 'required|string|max:255',
            'categ_id' => 'nullable|exists:article_categories,id',
            'photo'    => 'nullable|image|max:10240',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $this->convertToWebp($request->file('photo'));
        }

        $article = Article::create([
            'titre'    => $request->titre,
            'contenu'  => $request->contenu,
            'photo'    => $photoPath,
            'categ_id' => $request->categ_id ?: null,
            'ordre'    => $request->ordre ?? 0,
            'active'   => true,
        ]);

        return response()->json([
            'status'  => true,
            'article' => $this->formatItem($article),
            'message' => 'Article ajouté.',
        ]);
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return response()->json([
            'status'  => true,
            'detail'  => $this->formatItem($article),
            'message' => '',
        ]);
    }

    public function update($id, Request $request)
    {
        $article = Article::findOrFail($id);

        $photoPath = $article->photo;
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            $photoPath = $this->convertToWebp($request->file('photo'));
        }

        $article->update([
            'titre'    => $request->titre    ?? $article->titre,
            'contenu'  => $request->contenu  ?? $article->contenu,
            'photo'    => $photoPath,
            'categ_id' => $request->has('categ_id') ? ($request->categ_id ?: null) : $article->categ_id,
            'ordre'    => $request->ordre    ?? $article->ordre,
            'active'   => $request->has('active') ? (bool)$request->active : $article->active,
        ]);

        return response()->json(['status' => true, 'message' => 'Article mis à jour.']);
    }

    public function destroy(Request $request)
    {
        $article = Article::find($request->champ_id);
        if (!$article) return response()->json(['status' => false, 'message' => 'Introuvable.'], 404);

        if ($article->photo) Storage::disk('public')->delete($article->photo);
        $article->delete();

        return response()->json(['status' => true, 'message' => 'Article supprimé.']);
    }

    // -------------------------------------------------------------------------
    // Public vitrine — 10 par catégorie
    // -------------------------------------------------------------------------

    public function show_public($id)
    {
        $article = Article::where('active', true)->findOrFail($id);

        // Articles de la même catégorie (suggestions)
        $suggestions = Article::where('active', true)
            ->where('categ_id', $article->categ_id)
            ->where('id', '!=', $id)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get()
            ->map(fn($i) => [
                'id'        => $i->id,
                'titre'     => $i->titre,
                'photo_url' => $this->buildPhotoUrl($i->photo),
                'created_at'=> $i->created_at,
            ]);

        return response()->json([
            'status'      => true,
            'article'     => $this->formatItem($article),
            'suggestions' => $suggestions,
            'message'     => '',
        ]);
    }

    public function index_public()
    {
        $categories = ArticleCategorie::orderBy('desc')->get();

        $grouped = [];
        foreach ($categories as $cat) {
            $total = Article::where('active', true)->where('categ_id', $cat->id)->count();
            if ($total === 0) continue;

            $items = Article::where('active', true)->where('categ_id', $cat->id)
                ->orderBy('ordre')->orderBy('id', 'desc')->limit(10)->get();

            $grouped[] = [
                'categ_id'   => $cat->id,
                'categ_desc' => $cat->desc,
                'total'      => $total,
                'articles'   => $items->map(fn($i) => [
                    'id'        => $i->id,
                    'titre'     => $i->titre,
                    'contenu'   => $i->contenu,
                    'photo_url' => $this->buildPhotoUrl($i->photo),
                    'created_at'=> $i->created_at,
                ])->values(),
            ];
        }

        // Sans catégorie
        $total_sc = Article::where('active', true)->whereNull('categ_id')->count();
        if ($total_sc > 0) {
            $items_sc = Article::where('active', true)->whereNull('categ_id')
                ->orderBy('ordre')->orderBy('id', 'desc')->limit(10)->get();
            $grouped[] = [
                'categ_id'   => null,
                'categ_desc' => 'Autres',
                'total'      => $total_sc,
                'articles'   => $items_sc->map(fn($i) => [
                    'id'        => $i->id,
                    'titre'     => $i->titre,
                    'contenu'   => $i->contenu,
                    'photo_url' => $this->buildPhotoUrl($i->photo),
                    'created_at'=> $i->created_at,
                ])->values(),
            ];
        }

        return response()->json(['status' => true, 'grouped' => $grouped, 'message' => '']);
    }

    public function load_more(Request $request)
    {
        $categ_id = $request->categ_id;
        $offset   = (int)($request->offset ?? 10);
        $limit    = (int)($request->limit  ?? 10);

        $query = Article::where('active', true)->orderBy('ordre')->orderBy('id', 'desc');

        if ($categ_id === 'null' || $categ_id === null || $categ_id === '') {
            $query->whereNull('categ_id');
        } else {
            $query->where('categ_id', (int)$categ_id);
        }

        $total   = $query->count();
        $items   = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'status'   => true,
            'articles' => $items->map(fn($i) => [
                'id'        => $i->id,
                'titre'     => $i->titre,
                'contenu'   => $i->contenu,
                'photo_url' => $this->buildPhotoUrl($i->photo),
                'created_at'=> $i->created_at,
            ])->values(),
            'total'    => $total,
            'offset'   => $offset + $items->count(),
            'has_more' => ($offset + $limit) < $total,
            'message'  => '',
        ]);
    }

    // -------------------------------------------------------------------------
    // WebP conversion
    // -------------------------------------------------------------------------

    private function convertToWebp($file): ?string
    {
        if (!extension_loaded('gd')) return null;
        $mime = $file->getMimeType();

        $source = match ($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png'               => imagecreatefrompng($file->getRealPath()),
            'image/gif'               => imagecreatefromgif($file->getRealPath()),
            'image/webp'              => imagecreatefromwebp($file->getRealPath()),
            default                   => null,
        };
        if (!$source) return null;

        $w = imagesx($source); $h = imagesy($source);
        if ($w > 1200) {
            $r = 1200 / $w;
            $img = imagecreatetruecolor(1200, (int)($h * $r));
            if (in_array($mime, ['image/png', 'image/webp'])) {
                imagealphablending($img, false); imagesavealpha($img, true);
                imagefill($img, 0, 0, imagecolorallocatealpha($img, 0, 0, 0, 127));
            }
            imagecopyresampled($img, $source, 0, 0, 0, 0, 1200, (int)($h * $r), $w, $h);
            imagedestroy($source);
        } else {
            $img = $source;
        }

        $uuid    = Str::uuid();
        $tmp     = sys_get_temp_dir() . '/' . $uuid . '.webp';
        imagewebp($img, $tmp, 82);
        imagedestroy($img);

        $path   = 'articles/' . $uuid . '.webp';
        $stored = Storage::disk('public')->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $stored ? $path : null;
    }
}
