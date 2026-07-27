<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateProductImagesToSupabase extends Command
{
    protected $signature = 'supabase:migrate-products';

    protected $description = 'Memindahkan seluruh gambar produk dari local ke Supabase Storage';

    public function handle(SupabaseStorageService $storage)
    {
        $products = Product::whereNotNull('image')->get();

        $this->info("Total produk : {$products->count()}");

        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {

            $localPath = storage_path('app/public/products/' . basename($product->image));

if (!File::exists($localPath)) {
    $this->newLine();
    $this->warn("File tidak ditemukan: {$localPath}");
    $bar->advance();
    continue;
}

            if (!File::exists($localPath)) {
                $this->newLine();
                $this->warn("File tidak ditemukan : {$product->image}");
                $bar->advance();
                continue;
            }

            try {

                $uploaded = $storage->uploadProduct(
                    new \Illuminate\Http\UploadedFile(
                        $localPath,
                        basename($localPath),
                        mime_content_type($localPath),
                        null,
                        true
                    )
                );

                $product->update([
                    'image' => $uploaded,
                ]);

                $this->newLine();
                $this->info("✔ {$product->name}");

            } catch (\Throwable $e) {

                $this->newLine();
                $this->error($product->name);

                $this->line($e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info("Migrasi selesai.");
    }
}