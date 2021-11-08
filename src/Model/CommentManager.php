<?php


namespace Model;


use Entity\Comment;

class CommentManager extends BaseManager
{
    /**
     * @param bool $array
     * @return array
     */
    public function getAllComments(bool $array = false): array
    {
        $query = $this->db->query('SELECT * FROM comments');

        if ($array) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Comment');
        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getCommentsByPostId(int $postId, bool $array = false): array
    {
        $query = $this->db->prepare('SELECT * FROM comments WHERE postId = :postId');
        $query->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $query->execute();

        if ($array) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Comment');
        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @return Comment|bool|array
     */
    public function getCommentById(int $id, bool $array = false)
    {
        $query = $this->db->prepare('SELECT * FROM comments WHERE id = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        if ($array) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }

        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Comment');
        return $query->fetch();
    }

    public function getCommentsByAuthorId(int $authorId): array
    {
        $query = $this->db->prepare('SELECT * FROM comments WHERE authorId = :authorId');
        $query->bindValue(':authorId', $authorId, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Comment');

        return $query->fetchAll();
    }

    /**
     * @param Comment $comment
     * @param bool $getArray
     * @return array|bool|Comment
     */
    public function addComment(Comment $comment, bool $getArray = false)
    {
        $insert = $this->db->prepare('INSERT INTO comments (postId, authorId, content) VALUES (:postId, :authorId, :content)');
        $insert->bindValue(':postId', $comment->getPostId(), \PDO::PARAM_INT);
        $insert->bindValue(':authorId', $comment->getAuthorId(), \PDO::PARAM_INT);
        $insert->bindValue(':content', nl2br(htmlspecialchars($comment->getContent())), \PDO::PARAM_STR);

        return $insert->execute() ? $this->getCommentById($this->db->lastInsertId(), $getArray) : false;
    }

    /**
     * @param Comment $comment
     * @param bool $getArray
     * @return array|bool|Comment
     */
    public function updateComment(Comment $comment, bool $getArray = false)
    {
        $update = $this->db->prepare('UPDATE comments SET content = :content WHERE id = :id');
        $update->bindValue(':content', nl2br(htmlspecialchars($comment->getContent())), \PDO::PARAM_STR);
        $update->bindValue(':id', $comment->getId(), \PDO::PARAM_INT);

        return $update->execute() ? $this->getCommentById($comment->getId(), $getArray) : false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteCommentById(int $id): bool
    {
        $delete = $this->db->prepare('DELETE FROM comments WHERE id = :id');
        $delete->bindValue(':id', $id, \PDO::PARAM_INT);

        return $delete->execute();
    }

    /**
     * @param int $postId
     * @return bool
     */
    public function deleteCommentsByPostId(int $postId): bool
    {
        $delete = $this->db->prepare('DELETE FROM comments WHERE postId = :postId');
        $delete->bindValue(':postId', $postId, \PDO::PARAM_INT);

        return $delete->execute();
    }

    /**
     * @param int $authorId
     * @return bool
     */
    public function deleteCommentsByAuthorId(int $authorId): bool
    {
        $delete = $this->db->prepare('DELETE FROM comments WHERE authorId = :authorId');
        $delete->bindValue(':authorId', $authorId, \PDO::PARAM_INT);

        return $delete->execute();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function commentExists(int $id): bool
    {
        return (bool)$this->getCommentById($id);
    }
}