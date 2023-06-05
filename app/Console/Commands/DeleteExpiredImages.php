<?php

namespace App\Console\Commands;

use App\Models\GeneratedImage;
use Illuminate\Console\Command;

class DeleteExpiredImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $generatedImages = GeneratedImage::expired()->get();
        foreach ($generatedImages as $generatedImage) {
            $handler = $generatedImage->storageProvider->handler;
            $delete = $handler::delete($generatedImage->path);
            $generatedImage->delete();
        }
    }
}
