<?php
/**
 * Classe Upload - Gestion des fichiers uploadés
 * TATAVERNIS - Maison de Parfumerie Premium
 */

class Upload
{
    private array $errors = [];

    /**
     * Uploader une image
     */
    public function uploadImage(array $file, string $directory = 'products'): ?string
    {
        $this->errors = [];

        // Vérifier les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadError($file['error']);
            return null;
        }

        // Vérifier le type MIME
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            $this->errors[] = "Type de fichier non autorisé. Formats acceptés: JPG, PNG, WebP, GIF";
            return null;
        }

        // Vérifier la taille
        if ($file['size'] > MAX_FILE_SIZE) {
            $this->errors[] = "Le fichier est trop volumineux. Taille maximum: " . (MAX_FILE_SIZE / 1024 / 1024) . " Mo";
            return null;
        }

        // Générer un nom unique
        $extension = $this->getExtension($mimeType);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        
        // Créer le répertoire si nécessaire
        $uploadDir = UPLOADS_PATH . '/' . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . '/' . $filename;

        // Déplacer le fichier
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->errors[] = "Erreur lors de l'upload du fichier.";
            return null;
        }

        // Optimiser l'image
        $this->optimizeImage($destination, $mimeType);

        return $directory . '/' . $filename;
    }

    /**
     * Uploader une vidéo
     */
    public function uploadVideo(array $file, string $directory = 'videos'): ?string
    {
        $this->errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadError($file['error']);
            return null;
        }

        // Vérifier le type MIME
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, ALLOWED_VIDEO_TYPES)) {
            $this->errors[] = "Type de vidéo non autorisé. Formats acceptés: MP4, WebM, OGG";
            return null;
        }

        // Vérifier la taille
        if ($file['size'] > MAX_VIDEO_SIZE) {
            $this->errors[] = "La vidéo est trop volumineuse. Taille maximum: " . (MAX_VIDEO_SIZE / 1024 / 1024) . " Mo";
            return null;
        }

        // Générer un nom unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;

        // Créer le répertoire si nécessaire
        $uploadDir = UPLOADS_PATH . '/' . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->errors[] = "Erreur lors de l'upload de la vidéo.";
            return null;
        }

        return $directory . '/' . $filename;
    }

    /**
     * Uploader plusieurs images
     */
    public function uploadMultipleImages(array $files, string $directory = 'products'): array
    {
        $uploaded = [];
        
        // Réorganiser le tableau $_FILES
        $count = count($files['name']);
        
        for ($i = 0; $i < $count; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];

            $path = $this->uploadImage($file, $directory);
            if ($path) {
                $uploaded[] = $path;
            }
        }

        return $uploaded;
    }

    /**
     * Supprimer un fichier
     */
    public function delete(string $path): bool
    {
        $fullPath = UPLOADS_PATH . '/' . $path;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Optimiser une image
     */
    private function optimizeImage(string $path, string $mimeType): void
    {
        $maxWidth = 1920;
        $quality = 85;

        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $image = imagecreatefrompng($path);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($path);
                break;
            default:
                return;
        }

        if (!$image) return;

        $width = imagesx($image);
        $height = imagesy($image);

        // Redimensionner si nécessaire
        if ($width > $maxWidth) {
            $ratio = $maxWidth / $width;
            $newWidth = $maxWidth;
            $newHeight = (int) ($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            // Préserver la transparence pour PNG
            if ($mimeType === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Sauvegarder
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($image, $path, $quality);
                break;
            case 'image/png':
                imagepng($image, $path, 8);
                break;
            case 'image/webp':
                imagewebp($image, $path, $quality);
                break;
        }

        imagedestroy($image);
    }

    /**
     * Créer une miniature
     */
    public function createThumbnail(string $sourcePath, int $width = 300, int $height = 300): ?string
    {
        $fullPath = UPLOADS_PATH . '/' . $sourcePath;
        
        if (!file_exists($fullPath)) {
            return null;
        }

        $info = getimagesize($fullPath);
        if (!$info) return null;

        $mimeType = $info['mime'];

        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($fullPath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($fullPath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($fullPath);
                break;
            default:
                return null;
        }

        $srcWidth = imagesx($image);
        $srcHeight = imagesy($image);

        // Calculer les dimensions du crop
        $srcRatio = $srcWidth / $srcHeight;
        $dstRatio = $width / $height;

        if ($srcRatio > $dstRatio) {
            $cropWidth = (int) ($srcHeight * $dstRatio);
            $cropHeight = $srcHeight;
            $cropX = (int) (($srcWidth - $cropWidth) / 2);
            $cropY = 0;
        } else {
            $cropWidth = $srcWidth;
            $cropHeight = (int) ($srcWidth / $dstRatio);
            $cropX = 0;
            $cropY = (int) (($srcHeight - $cropHeight) / 2);
        }

        $thumbnail = imagecreatetruecolor($width, $height);
        
        if ($mimeType === 'image/png') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
        }

        imagecopyresampled(
            $thumbnail, $image,
            0, 0, $cropX, $cropY,
            $width, $height, $cropWidth, $cropHeight
        );

        // Générer le nom de la miniature
        $pathInfo = pathinfo($sourcePath);
        $thumbPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        $thumbFullPath = UPLOADS_PATH . '/' . $thumbPath;

        // Créer le dossier thumbs si nécessaire
        $thumbDir = dirname($thumbFullPath);
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }

        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($thumbnail, $thumbFullPath, 85);
                break;
            case 'image/png':
                imagepng($thumbnail, $thumbFullPath, 8);
                break;
            case 'image/webp':
                imagewebp($thumbnail, $thumbFullPath, 85);
                break;
        }

        imagedestroy($image);
        imagedestroy($thumbnail);

        return $thumbPath;
    }

    /**
     * Obtenir les erreurs
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Obtenir l'extension depuis le type MIME
     */
    private function getExtension(string $mimeType): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];

        return $extensions[$mimeType] ?? 'jpg';
    }

    /**
     * Obtenir le message d'erreur d'upload
     */
    private function getUploadError(int $errorCode): string
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE => "Le fichier dépasse la taille maximale autorisée par le serveur.",
            UPLOAD_ERR_FORM_SIZE => "Le fichier dépasse la taille maximale autorisée.",
            UPLOAD_ERR_PARTIAL => "Le fichier n'a été que partiellement uploadé.",
            UPLOAD_ERR_NO_FILE => "Aucun fichier n'a été uploadé.",
            UPLOAD_ERR_NO_TMP_DIR => "Dossier temporaire manquant.",
            UPLOAD_ERR_CANT_WRITE => "Échec de l'écriture du fichier sur le disque.",
            UPLOAD_ERR_EXTENSION => "Upload bloqué par une extension PHP."
        ];

        return $errors[$errorCode] ?? "Erreur inconnue lors de l'upload.";
    }
}
