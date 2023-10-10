<?php

namespace App\services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderService
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function uploadFile(UploadedFile $path, $directory = null) {
            if (!$directory)
                $directory = $this->getParameter('person_directory');
            $originalFilename = pathinfo($path->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$path->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $path->move(
                    $directory,
                    $newFilename
                );
            } catch (FileException $e) {

                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            return $newFilename ;
    }
}