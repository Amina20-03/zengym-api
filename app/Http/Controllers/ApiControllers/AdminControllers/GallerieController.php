<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Gallerie;
use App\Models\PhotoInstructeurCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GallerieController extends Controller
{
    /**
     * Construit l'URL publique d'une image
     */
    private function buildPhotoUrl(string $path): string
    {
        $base = 'https://www.zengymhealth.com/zen_gym_ws/project';
        return $base . '/storage/app/public/' . $path;
    }

    /**
     * Liste toutes les images groupées par catégorie (admin)
     */
    public function index()
    {
        // Toutes les catégories
        $categories = PhotoInstructeurCategorie::orderBy('desc')->get();

        // Images avec leur catégorie
        $liste = Gallerie::orderBy('categ_id')->orderBy('ordre')->orderBy('id', 'desc')
            ->get()
            ->map(fn($item) => $this->formatItem($item));

        // Images groupées par catégorie pour faciliter l'affichage
        $grouped = [];
        foreach ($categories as $cat) {
            $grouped[] = [
                'categ_id'   => $cat->id,
                'categ_desc' => $cat->desc,
                'images'     => $liste->where('categ_id', $cat->id)->values(),
            ];
        }
        // Images sans catégorie
        $sans_categ = $liste->whereNull('categ_id')->values();
        if ($sans_categ->count() > 0) {
            $grouped[] = [
                'categ_id'   => null,
                'categ_desc' => 'Sans catégorie',
                'images'     => $sans_categ,
            ];
        }

        return response()->json([
            'status'     => true,
            'liste'      => $liste,
            'grouped'    => $grouped,
            'categories' => $categories,
            'message'    => '',
        ]);
    }

    /**
     * Liste publique pour la vitrine — 10 images par catégorie + total
     */
    public function index_public()
    {
        $categories = PhotoInstructeurCategorie::orderBy('desc')->get();

        $grouped = [];
        foreach ($categories as $cat) {
            $total = Gallerie::where('active', true)->where('categ_id', $cat->id)->count();
            if ($total === 0) continue;

            $imgs = Gallerie::where('active', true)
                ->where('categ_id', $cat->id)
                ->orderBy('ordre')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();

            $grouped[] = [
                'categ_id'   => $cat->id,
                'categ_desc' => $cat->desc,
                'total'      => $total,
                'images'     => $imgs->map(fn($item) => [
                    'id'        => $item->id,
                    'titre'     => $item->titre,
                    'photo_url' => $this->buildPhotoUrl($item->photo),
                ])->values(),
            ];
        }

        // Sans catégorie
        $total_sc = Gallerie::where('active', true)->whereNull('categ_id')->count();
        if ($total_sc > 0) {
            $imgs_sc = Gallerie::where('active', true)
                ->whereNull('categ_id')
                ->orderBy('ordre')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();

            $grouped[] = [
                'categ_id'   => null,
                'categ_desc' => 'Autres',
                'total'      => $total_sc,
                'images'     => $imgs_sc->map(fn($item) => [
                    'id'        => $item->id,
                    'titre'     => $item->titre,
                    'photo_url' => $this->buildPhotoUrl($item->photo),
                ])->values(),
            ];
        }

        return response()->json([
            'status'  => true,
            'grouped' => $grouped,
            'message' => '',
        ]);
    }

    /**
     * Charger plus d'images pour une catégorie donnée
     * GET gallerie/public/load_more?categ_id=X&offset=10&limit=10
     */
    public function load_more(Request $request)
    {
        $categ_id = $request->categ_id; // null = sans catégorie
        $offset   = (int)($request->offset ?? 10);
        $limit    = (int)($request->limit  ?? 10);

        $query = Gallerie::where('active', true)
            ->orderBy('ordre')
            ->orderBy('id', 'desc');

        if ($categ_id === 'null' || $categ_id === null || $categ_id === '') {
            $query->whereNull('categ_id');
        } else {
            $query->where('categ_id', (int)$categ_id);
        }

        $total  = $query->count();
        $images = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'status'   => true,
            'images'   => $images->map(fn($item) => [
                'id'        => $item->id,
                'titre'     => $item->titre,
                'photo_url' => $this->buildPhotoUrl($item->photo),
            ])->values(),
            'total'    => $total,
            'offset'   => $offset + $images->count(),
            'has_more' => ($offset + $limit) < $total,
            'message'  => '',
        ]);
    }

    /**
     * Upload une ou plusieurs images
     * Champs : images[], categ_id, titres[], descriptions[]
     */
    public function store(Request $request)
    {
        $request->validate([
            'images'         => 'required|array|min:1',
            'images.*'       => 'required|image|max:10240',
            'categ_id'       => 'nullable|exists:photo_instructeur_categories,id',
            'titres'         => 'nullable|array',
            'titres.*'       => 'nullable|string|max:255',
            'descriptions'   => 'nullable|array',
            'descriptions.*' => 'nullable|string',
        ]);

        if (!extension_loaded('gd')) {
            return response()->json([
                'status'  => false,
                'message' => 'Extension GD non disponible sur le serveur.',
            ], 500);
        }

        $saved = [];

        foreach ($request->file('images') as $index => $file) {
            $webpPath = $this->convertToWebp($file);
            if (!$webpPath) continue;

            $item = Gallerie::create([
                'titre'       => $request->titres[$index] ?? null,
                'description' => $request->descriptions[$index] ?? null,
                'photo'       => $webpPath,
                'categ_id'    => $request->categ_id ?: null,
                'ordre'       => 0,
                'active'      => true,
            ]);

            $saved[] = [
                'id'        => $item->id,
                'photo_url' => $this->buildPhotoUrl($webpPath),
            ];
        }

        return response()->json([
            'status'  => true,
            'saved'   => $saved,
            'message' => count($saved) . ' image(s) ajoutée(s).',
        ]);
    }

    /**
     * Modifier titre / description / categ_id / ordre / active
     */
    public function update($id, Request $request)
    {
        $item = Gallerie::findOrFail($id);

        $item->update([
            'titre'       => $request->titre       ?? $item->titre,
            'description' => $request->description ?? $item->description,
            'categ_id'    => $request->has('categ_id') ? ($request->categ_id ?: null) : $item->categ_id,
            'ordre'       => $request->ordre        ?? $item->ordre,
            'active'      => $request->has('active') ? (bool)$request->active : $item->active,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Image mise à jour.',
        ]);
    }

    /**
     * Supprimer une image
     */
    public function destroy(Request $request)
    {
        $id   = $request->champ_id;
        $item = Gallerie::find($id);

        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Image introuvable.'], 404);
        }

        Storage::disk('public')->delete($item->photo);
        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Image supprimée.',
        ]);
    }

    // -----------------------------------------------------------------------
    // Helpers privés
    // -----------------------------------------------------------------------

    private function formatItem($item): array
    {
        return [
            'id'          => $item->id,
            'titre'       => $item->titre,
            'description' => $item->description,
            'photo'       => $item->photo,
            'photo_url'   => $this->buildPhotoUrl($item->photo),
            'categ_id'    => $item->categ_id,
            'categ_desc'  => $item->categ_id
                ? PhotoInstructeurCategorie::where('id', $item->categ_id)->value('desc')
                : null,
            'ordre'       => $item->ordre,
            'active'      => $item->active,
            'created_at'  => $item->created_at,
        ];
    }

    private function convertToWebp($file): ?string
    {
        $mime = $file->getMimeType();

        $source = match ($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png'               => imagecreatefrompng($file->getRealPath()),
            'image/gif'               => imagecreatefromgif($file->getRealPath()),
            'image/webp'              => imagecreatefromwebp($file->getRealPath()),
            default                   => null,
        };

        if (!$source) return null;

        $origWidth  = imagesx($source);
        $origHeight = imagesy($source);

        $maxWidth = 1200;
        if ($origWidth > $maxWidth) {
            $ratio     = $maxWidth / $origWidth;
            $newWidth  = $maxWidth;
            $newHeight = (int)($origHeight * $ratio);
            $resized   = imagecreatetruecolor($newWidth, $newHeight);

            if (in_array($mime, ['image/png', 'image/webp'])) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagefill($resized, 0, 0, imagecolorallocatealpha($resized, 0, 0, 0, 127));
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($source);
            $image = $resized;
        } else {
            $image = $source;
        }

        $uuid    = Str::uuid();
        $tmpPath = sys_get_temp_dir() . '/' . $uuid . '.webp';

        imagewebp($image, $tmpPath, 82);
        imagedestroy($image);

        $relativePath = 'gallerie/' . $uuid . '.webp';
        $stored = Storage::disk('public')->put($relativePath, file_get_contents($tmpPath));
        @unlink($tmpPath);

        return $stored ? $relativePath : null;
    }
}
