<?php

namespace App\EntityListener;

use App\Entity\Medias;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MediaListener.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class MediaListener
{
    /** @var string */
    private string $uploadDir;

    /** @var string */
    private string $uploadAbsoluteDir;

    /**
     * MediaListener constructor.
     *
     * @param string $uploadDir
     * @param string $uploadAbsoluteDir
     */
    public function __construct(string $uploadDir, string $uploadAbsoluteDir)
    {
        $this->uploadDir = $uploadDir;
        $this->uploadAbsoluteDir = $uploadAbsoluteDir;
    }

    /**
     * @param Medias $media
     * @return void
     */
    public function prePersist(Medias $media): void
    {
        $this->upload($media);
    }

    /**
     * @param Medias $media
     * @return void
     */
    public function preUpdate(Medias $media): void
    {
        $this->upload($media);
    }

    /**
     * @param Medias $media
     * @return void
     */
    public function upload(Medias $media): void
    {
        if ($media->getFile() instanceof UploadedFile) {
            $filename = sprintf("%s.%s", bin2hex(random_bytes(6)), $media->getFile()->getClientOriginalExtension());
            $media->getFile()->move($this->uploadAbsoluteDir, $filename);
            $media->setPath(sprintf("%s/%s", $this->uploadDir, $filename));
        }
    }
}