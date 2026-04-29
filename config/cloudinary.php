<?php
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryConfig {
    private static string $cloudName = 'your_cloud_name';
    private static string $apiKey    = 'your_api_key';
    private static string $apiSecret = 'your_api_secret';

    private static bool $configured = false;

    private static function isConfigured(): bool {
        return self::$cloudName !== 'your_cloud_name' && !empty(self::$apiKey);
    }

    public static function configure(): void {
        if (self::$configured) return;
        Configuration::instance([
            'cloud' => [
                'cloud_name' => self::$cloudName,
                'api_key'    => self::$apiKey,
                'api_secret' => self::$apiSecret,
            ],
            'url' => ['secure' => true]
        ]);
        self::$configured = true;
    }

    public static function upload(string $filePath, array $options = []): ?string {
        // Use local storage if Cloudinary is not configured
        if (!self::isConfigured()) {
            return self::uploadLocal($filePath);
        }
        self::configure();
        try {
            $api    = new UploadApi();
            $result = $api->upload($filePath, array_merge([
                'folder'       => 'company_management/profiles',
                'quality'      => 'auto',
                'fetch_format' => 'auto',
            ], $options));
            return $result['secure_url'] ?? null;
        } catch (\Exception $e) {
            error_log('Cloudinary Upload Error: ' . $e->getMessage());
            return self::uploadLocal($filePath);
        }
    }

    private static function uploadLocal(string $filePath): ?string {
        $uploadDir = __DIR__ . '/../uploads/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $ext      = strtolower(pathinfo($filePath, PATHINFO_EXTENSION) ?: 'jpg');
        $filename = uniqid('profile_', true) . '.' . $ext;
        if (move_uploaded_file($filePath, $uploadDir . $filename)) {
            return BASE_URL . '/uploads/profiles/' . $filename;
        }
        // fallback for already-moved tmp files (copy instead)
        if (copy($filePath, $uploadDir . $filename)) {
            return BASE_URL . '/uploads/profiles/' . $filename;
        }
        return null;
    }

    public static function delete(string $publicId): bool {
        if (!self::isConfigured()) return true;
        self::configure();
        try {
            $api    = new UploadApi();
            $result = $api->destroy($publicId);
            return ($result['result'] ?? '') === 'ok';
        } catch (\Exception $e) {
            error_log('Cloudinary Delete Error: ' . $e->getMessage());
            return false;
        }
    }
}
