<?php

namespace App\Http\Controllers\ApiControllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgrammeController extends Controller
{
    private function buildPhotoUrl(?string $path): ?string
    {
        if (!$path) return null;
        return 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/' . $path;
    }

    private function format(Programme $p): array
    {
        return [
            'id'              => $p->id,
            'instructeur_id'  => $p->instructeur_id,
            'titre'           => $p->titre,
            'description'     => $p->description,
            'duree_semaines'  => $p->duree_semaines,
            'niveau'          => $p->niveau,
            'photo'           => $p->photo,
            'photo_url'       => $this->buildPhotoUrl($p->photo),
            'actif'           => $p->actif,
            'created_at'      => $p->created_at,
        ];
    }
   public function index_public()
{
    $programmes = \App\Models\Programme::select('id', 'titre', 'nom', 'description', 'niveau', 
                                               'duree_semaines', 'photo_url', 'prix')
        ->where('statut', 'actif')        // ou 'publié'
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'status' => true,
        'programmes' => $programmes
    ]);
}

    /*public function index(Request $request)
    {
        $instructeur_id = $request->instructeur_id;
        $liste = Programme::where('instructeur_id', $instructeur_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => $this->format($p));

        return response()->json(['status' => true, 'liste' => $liste, 'message' => '']);
    }*/
        public function index(Request $request)
{
    $query = Programme::query();

    // Si un instructeur_id est fourni (cas instructeur), on filtre.
    // Sinon (cas admin), on retourne tous les programmes.
    if ($request->filled('instructeur_id')) {
        $query->where('instructeur_id', $request->instructeur_id);
    }

    $liste = $query->orderBy('created_at', 'desc')
        ->get()
        ->map(fn($p) => $this->format($p));

    return response()->json(['status' => true, 'liste' => $liste, 'message' => '']);
}

    public function store(Request $request)
    {
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $this->storePhoto($request->file('photo'));
        }

        $programme = Programme::create([
            'instructeur_id' => $request->instructeur_id,
            'titre'          => $request->titre,
            'description'    => $request->description,
            'duree_semaines' => $request->duree_semaines ?: null,
            'niveau'         => $request->niveau ?: null,
            'photo'          => $photoPath,
            'actif'          => true,
        ]);

        // Upload vidéos si présentes
        if ($request->hasFile('videos')) {
            $this->storeVideos($request->file('videos'), $programme->id);
        }

        return response()->json([
            'status'     => true,
            'programme'  => $this->format($programme),
            'message'    => 'Programme créé.',
        ]);
    }

    public function show($id)
    {
        $p      = Programme::findOrFail($id);
        $videos = \Illuminate\Support\Facades\DB::table('programme_videos')
            ->where('programme_id', $id)->get()
            ->map(fn($v) => [
                'id'    => $v->id,
                'lib'   => $v->lib,
                'path'  => $v->path,
                'url'   => 'https://www.zengymhealth.com/zen_gym_ws/project/storage/app/public/' . $v->path,
            ]);

        return response()->json([
            'status'  => true,
            'detail'  => $this->format($p),
            'videos'  => $videos,
            'message' => '',
        ]);
    }

    public function update(Request $request, $id)
    {
        $p = Programme::findOrFail($id);

        $photoPath = $p->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            $photoPath = $this->storePhoto($request->file('photo'));
        }

        $p->update([
            'titre'          => $request->titre          ?? $p->titre,
            'description'    => $request->description    ?? $p->description,
            'duree_semaines' => $request->duree_semaines ?: $p->duree_semaines,
            'niveau'         => $request->niveau         ?: $p->niveau,
            'photo'          => $photoPath,
            'actif'          => $request->has('actif') ? (bool)$request->actif : $p->actif,
        ]);

        // Upload nouvelles vidéos si présentes
        if ($request->hasFile('videos')) {
            $this->storeVideos($request->file('videos'), $id);
        }

        return response()->json(['status' => true, 'message' => 'Programme mis à jour.']);
    }

    public function deleteVideo($video_id)
    {
        $video = \Illuminate\Support\Facades\DB::table('programme_videos')->where('id', $video_id)->first();
        if ($video) {
            Storage::disk('public')->delete($video->path);
            \Illuminate\Support\Facades\DB::table('programme_videos')->where('id', $video_id)->delete();
        }
        return response()->json(['status' => true, 'message' => 'Vidéo supprimée.']);
    }

    private function storeVideos(array $files, int $programme_id): void
    {
        $disk = Storage::disk('public');
        if (!$disk->exists('programmes/videos')) {
            $disk->makeDirectory('programmes/videos');
        }

        foreach ($files as $file) {
            $uuid = Str::uuid();
            $ext  = $file->getClientOriginalExtension() ?: 'mp4';
            $path = 'programmes/videos/' . $uuid . '.' . $ext;
            $disk->put($path, file_get_contents($file->getRealPath()));

            \Illuminate\Support\Facades\DB::table('programme_videos')->insert([
                'programme_id' => $programme_id,
                'lib'          => $file->getClientOriginalName(),
                'path'         => $path,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    public function destroy($id)
    {
        $p = Programme::find($id);
        if ($p) {
            if ($p->photo) Storage::disk('public')->delete($p->photo);
            $p->delete();
        }
        return response()->json(['status' => true, 'message' => 'Programme supprimé.']);
    }

    private function storePhoto($file): ?string
    {
        $disk = Storage::disk('public');
        if (!$disk->exists('programmes')) $disk->makeDirectory('programmes');

        if (!extension_loaded('gd')) {
            $uuid = Str::uuid();
            $ext  = $file->getClientOriginalExtension() ?: 'jpg';
            $path = 'programmes/' . $uuid . '.' . $ext;
            $disk->put($path, file_get_contents($file->getRealPath()));
            return $path;
        }

        $mime   = $file->getMimeType();
        $source = match($mime) {
            'image/jpeg','image/jpg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png'              => imagecreatefrompng($file->getRealPath()),
            'image/webp'             => imagecreatefromwebp($file->getRealPath()),
            default                  => null,
        };
        if (!$source) return null;

        $w = imagesx($source); $h = imagesy($source);
        if ($w > 1200) {
            $r  = 1200 / $w; $nw = 1200; $nh = (int)($h * $r);
            $img = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($img, $source, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($source);
        } else { $img = $source; }

        $uuid = Str::uuid();
        $tmp  = sys_get_temp_dir() . '/' . $uuid . '.webp';
        imagewebp($img, $tmp, 85);
        imagedestroy($img);

        $path   = 'programmes/' . $uuid . '.webp';
        $stored = $disk->put($path, file_get_contents($tmp));
        @unlink($tmp);

        return $stored ? $path : null;
    }
}
