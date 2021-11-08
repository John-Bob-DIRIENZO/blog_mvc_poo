<?php


namespace Model;


use Entity\Image;

class ImageManager extends BaseManager
{
    public function getAllImages(bool $getArray = false)
    {
        $query = $this->db->query('SELECT * FROM images');

        if ($getArray) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Image');
        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @param bool $getArray
     * @return array|false|Image
     */
    public function getImageById(int $id, bool $getArray = false)
    {
        $query = $this->db->prepare('SELECT * FROM images WHERE id = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        if ($getArray) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Image');
        return $query->fetch();
    }

    public function addImage(Image $image, bool $getArray = false)
    {
        $insert = $this->db->prepare('INSERT INTO images (name) VALUES (:name)');
        $insert->bindValue(':name', $image->getName(), \PDO::PARAM_STR);

        return $insert->execute() ? $this->getImageById($this->db->lastInsertId(), $getArray) : false;
    }

    public function updateImage(Image $image, bool $getArray = false)
    {
        $update = $this->db->prepare('UPDATE images SET name = :name WHERE id = :id');
        $update->bindValue(':name', $image->getName(), \PDO::PARAM_STR);
        $update->bindValue(':id', $image->getId(), \PDO::PARAM_INT);

        return $update->execute() ? $this->getImageById($image->getId(), $getArray) : false;
    }

    public function deleteImage(int $id)
    {
        $delete = $this->db->prepare('DELETE FROM images WHERE id = :id');
        $delete->bindValue(':id', $id, \PDO::PARAM_INT);

        return $delete->execute();
    }

    /**
     * Uploads image and adds it in DB, return Image if success
     * false otherwise
     * @param $file
     * @return string|bool
     */
    public function uploadImage($file, bool $getArray = false)
    {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/Public/Images/';
        $targetFile = $targetDir . basename($file['name']);
        $maxSize = 1000000;
        $isGoodSize = $file['size'] < $maxSize;

        if (!empty($file['tmp_name'])) {
            $isImage = (bool)getimagesize($file['tmp_name']);
        }

        if ($isImage && $isGoodSize) {
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                return $this->addImage(new Image(['name' => $file['name']]), $getArray);
            }
        }
        return false;
    }
}