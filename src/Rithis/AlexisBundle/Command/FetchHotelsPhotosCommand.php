<?php

namespace Rithis\AlexisBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gaufrette\Filesystem;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class FetchHotelsPhotosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName("alexis:hotels:fetch_photos");
        $this->setDescription("Fetch hotels photos from Pegas Touristik");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $this->getContainer()->get('mongodb')->hotels;

        $hotels = $collection->find(array('photos_unprocessed' => array('$exists' => true)));

        foreach ($hotels as $hotel) {
            $photos = $this->fetchPhotos($hotel);

            $collection->update(array('_id' => $hotel['_id']), array(
                '$set' => array('photos' => $photos),
                '$unset' => array('photos_unprocessed' => 1)
            ));die;
        }
    }

    private function fetchPhotos($hotel)
    {
        $imagine = $this->getContainer()->get('alexis.imagine');
        $photos = array();

        foreach ($hotel['photos_unprocessed'] as $key => $photo) {
            $imageBytes = file_get_contents($photo['url']);
            $image = $imagine->load($imageBytes);
            $directory = sprintf("hotels/%s/%s", $hotel['_id'], md5($imageBytes));

            if ($image->getSize()->getWidth() < 300 || $image->getSize()->getHeight() < 200) {
                continue;
            }

            $photo['original'] = $this->savePhoto($directory, 'original.png', $image);

            if ($key == 0) {
                $thumbnail = $image->thumbnail(new Box(300, 200), ImageInterface::THUMBNAIL_OUTBOUND);
                $photo['big_thumbnail'] = $this->savePhoto($directory, 'big_thumbnail.png', $thumbnail);
            }

            $thumbnail = $image->thumbnail(new Box(64, 64), ImageInterface::THUMBNAIL_OUTBOUND);
            $photo['small_thumbnail'] = $this->savePhoto($directory, 'small_thumbnail.png', $thumbnail);

            $photos[] = $photo;
        }

        return $photos;
    }

    private function savePhoto($directory, $key, ImageInterface $image)
    {
        $fs = $this->getContainer()->get('alexis.fs');

        $content = $image->get('png');
        $checksum = md5($content);
        $key = rtrim($directory, '/') . '/' . $key;
        $url = sprintf($this->getContainer()->getParameter('alexis_hotels_photo_url'), $key);

        try {
            if ($fs->checksum($key) == $checksum) {
                return $url;
            }
        } catch (\RuntimeException $e) {
            // File not found so write new
        }

        $fs->write($key, $image->get('png'), true, array(
            'content-type' => 'image/png'
        ));

        return $url;
    }
}
