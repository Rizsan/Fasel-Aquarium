<?php

namespace App\Services\Supabase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    protected string $url;
    protected string $key;

    public function __construct()
    {
        $this->url = rtrim(env('SUPABASE_URL'), '/');
        $this->key = env('SUPABASE_SERVICE_ROLE_KEY');
    }

    /**
     * Upload file ke bucket
     */
    public function upload(UploadedFile $file, string $bucket, string $folder = ''): string
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        $path = $folder
            ? trim($folder,'/').'/'.$filename
            : $filename;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->key,
            'apikey'        => $this->key,
            'Content-Type'  => $file->getMimeType(),
        ])
        ->withBody(
            file_get_contents($file->getRealPath()),
            $file->getMimeType()
        )
        ->post(
            "{$this->url}/storage/v1/object/{$bucket}/{$path}"
        );

        if(!$response->successful()){
            throw new \Exception($response->body());
        }

        return $path;
    }

    /**
     * Upload avatar
     */
    public function uploadProfilePhoto(UploadedFile $file): string
    {
        return $this->upload(
            $file,
            env('SUPABASE_PROFILE_BUCKET')
        );
    }

    /**
     * Upload produk
     */
    public function uploadProduct(UploadedFile $file): string
    {
        return $this->upload(
            $file,
            env('SUPABASE_PRODUCT_BUCKET')
        );
    }

    /**
     * URL Public
     */
    public function url(string $bucket,string $path): string
    {
        return "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
    }

    /**
     * URL Avatar
     */
    public function profilePhotoUrl(string $path): string
    {
        return $this->url(
            env('SUPABASE_PROFILE_BUCKET'),
            $path
        );
    }

    /**
     * URL Produk
     */
    public function productUrl(string $path): string
    {
        return $this->url(
            env('SUPABASE_PRODUCT_BUCKET'),
            $path
        );
    }

    /**
     * Hapus file
     */
    public function delete(string $bucket,string $path): bool
    {
        return Http::withHeaders([
            'Authorization'=>'Bearer '.$this->key,
            'apikey'=>$this->key,
        ])
        ->delete(
            "{$this->url}/storage/v1/object/{$bucket}/{$path}"
        )
        ->successful();
    }
}