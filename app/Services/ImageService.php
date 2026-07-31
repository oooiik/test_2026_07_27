<?php

namespace App\Services;

use App\Entities\Image;
use App\Repositories\ImageRepository;
use League\Flysystem\FilesystemOperator;
use RuntimeException;

class ImageService
{
  protected const NOT_FOUND_PATH = 'not_found.png';
  protected const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

  public function __construct(
    protected FilesystemOperator $filesystem,
    protected ImageRepository $repositoryImage
  )
  {
  }

  /**
   * @param array{tmp_name: string, name: string, error: int}|null $file $_FILES
   */
  public function create(?array $file = null): Image
  {
    if ($file === null) {
      $path = $this->generatePath('png');
      $this->filesystem->write($path, $this->filesystem->read(self::NOT_FOUND_PATH));

      return $this->repositoryImage->save(new Image(0, $path));
    }

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
      throw new RuntimeException('Error upload images from $_FILES');
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
      throw new RuntimeException("Not Allowed extension: {$extension}");
    }

    $path = $this->generatePath($extension);

    $stream = fopen($file['tmp_name'], 'r');
    try {
      $this->filesystem->writeStream($path, $stream);
    } finally {
      if (is_resource($stream)) {
        fclose($stream); // in try cache becouse all time need close stream
      }
    }

    return $this->repositoryImage->save(new Image(0, $path));
  }

  public function getUrl(Image $image): string
  {
    return $this->filesystem->publicUrl($image->getPath());
  }

  public function getUrlById(int $id): string
  {
    $image = $this->repositoryImage->getById($id);
    return $this->filesystem->publicUrl($image->getPath());
  }

  protected function generatePath(string $extension): string
  {
    return bin2hex(random_bytes(16)) . '.' . $extension;
  }
}
