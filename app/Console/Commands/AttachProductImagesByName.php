<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachProductImagesByName extends Command
{
    protected $signature = 'products:attach-images
        {--source=uploads/users/products/by-name : Source directory on the public disk (relative to storage/app/public)}
        {--target=uploads/users/products : Target directory on the public disk (relative to storage/app/public)}
        {--dry-run : Show what would change without writing anything}';

    protected $description = 'Attach product images by matching image filenames to product names (accent-insensitive).';

    public function handle(): int
    {
        $disk = Storage::disk('public');

        $sourceDir = trim((string) $this->option('source'));
        $targetDir = trim((string) $this->option('target'));
        $dryRun = (bool) $this->option('dry-run');

        $sourceDir = $this->normalizeDir($sourceDir);
        $targetDir = $this->normalizeDir($targetDir);

        if (! $disk->exists($sourceDir)) {
            if (! $dryRun) {
                $disk->makeDirectory($sourceDir);
            }

            $this->warn($dryRun
                ? "Source directory not found (dry-run): {$sourceDir}"
                : "Source directory not found; created it: {$sourceDir}"
            );
            $this->line('Put your product images here (filenames should match product names):');
            $this->line(storage_path('app/public/' . $sourceDir));
            return self::SUCCESS;
        }

        if (! $disk->exists($targetDir) && ! $dryRun) {
            $disk->makeDirectory($targetDir);
        }

        $files = collect($disk->files($sourceDir))
            ->filter(fn (string $path) => $this->isSupportedImage($path))
            ->values();

        if ($files->isEmpty()) {
            $this->warn("No image files found in: {$sourceDir}");
            return self::SUCCESS;
        }

        // Map normalized base name => relative path on disk
        $fileMap = [];
        foreach ($files as $path) {
            $base = pathinfo($path, PATHINFO_FILENAME);
            $key = $this->normalizeKey($base);
            if ($key === '') {
                continue;
            }
            // Keep first match if duplicates exist
            $fileMap[$key] ??= $path;
        }

        $matched = 0;
        $missing = 0;
        $updated = 0;
        $created = 0;
        $copied = 0;

        $missingProducts = [];
        $matchedKeys = [];

        Product::query()->orderBy('id')->chunkById(200, function ($products) use (
            &$matched,
            &$missing,
            &$updated,
            &$created,
            &$copied,
            &$missingProducts,
            &$matchedKeys,
            $fileMap,
            $disk,
            $sourceDir,
            $targetDir,
            $dryRun
        ) {
            foreach ($products as $product) {
                $key = $this->normalizeKey((string) $product->name);

                $sourcePath = $fileMap[$key] ?? null;
                if ($sourcePath === null) {
                    $missing++;
                    if (count($missingProducts) < 50) {
                        $missingProducts[] = [
                            'id' => $product->id,
                            'name' => (string) $product->name,
                            'expected' => $key,
                        ];
                    }
                    continue;
                }

                $matched++;
                $matchedKeys[$key] = true;

                $filename = basename($sourcePath);
                $targetPath = $this->normalizeDir($targetDir) . '/' . $filename;
                $targetPath = ltrim($targetPath, '/');

                if ($sourcePath !== $targetPath) {
                    if (! $disk->exists($targetPath)) {
                        if (! $dryRun) {
                            $disk->copy($sourcePath, $targetPath);
                        }
                        $copied++;
                    }
                }

                $dbValue = $targetPath; // store relative to public disk, e.g. uploads/users/products/abc.jpg

                /** @var ProductImage|null $image */
                $image = $product->firstImage()->first();

                if ($image) {
                    if ((string) $image->image !== $dbValue) {
                        if (! $dryRun) {
                            $image->update(['image' => $dbValue]);
                        }
                        $updated++;
                    }
                } else {
                    if (! $dryRun) {
                        ProductImage::query()->create([
                            'product_id' => $product->id,
                            'image' => $dbValue,
                        ]);
                    }
                    $created++;
                }
            }
        });

        $this->info('Done.');
        $this->line("Matched products: {$matched}");
        $this->line("Missing images: {$missing}");
        $this->line("Images created: {$created}");
        $this->line("Images updated: {$updated}");
        $this->line("Files copied: {$copied}");

        if ($missing > 0 && ! empty($missingProducts)) {
            $this->newLine();
            $this->warn('Products missing matching images (showing up to 50):');
            foreach ($missingProducts as $row) {
                $this->line("- #{$row['id']} {$row['name']} (expected filename slug: {$row['expected']})");
            }
        }

        $unused = array_diff_key($fileMap, $matchedKeys);
        if (! empty($unused)) {
            $this->newLine();
            $this->warn('Unused image files (no matching product name):');
            $shown = 0;
            foreach ($unused as $k => $path) {
                $this->line("- {$path} (slug: {$k})");
                $shown++;
                if ($shown >= 50) {
                    $this->line('...');
                    break;
                }
            }
        }

        if ($dryRun) {
            $this->warn('Dry-run mode: no database or filesystem changes were made.');
        }

        return self::SUCCESS;
    }

    private function isSupportedImage(string $path): bool
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
    }

    private function normalizeKey(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        // Convert to slug-like key, accent-insensitive.
        $key = Str::slug($value, '-');

        return trim($key);
    }

    private function normalizeDir(string $dir): string
    {
        $dir = str_replace('\\', '/', $dir);
        $dir = trim($dir);
        $dir = trim($dir, '/');

        return $dir;
    }
}
