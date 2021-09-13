<?php


namespace Model;


use Entity\Comment;

class CommentManager extends BaseManager
{
    /**
     * @return array
     */
    public function getAllComments(): array
    {
        $query = $this->db->query('SELECT * FROM comments');
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Comment');

        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getCommentsByPostId(int $postId): array
    {
        $query = $this->db->prepare('SELECT * FROM comments WHERE postId = :postId');
        $query->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Comment');

        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @return Comment|bool
     */
    public function getCommentById(int $id)
    {
        $query = $this->db->prepare('SELECT * FROM comments WHERE id = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
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

    public function addComment(Comment $comment): Comment
    {
        $insert = $this->db->prepare('INSERT INTO comments (postId, authroId, content) VALUES (:postId, :authroId, :content)');
        $insert->bindValue(':postId', $comment->getPostId(), \PDO::PARAM_INT);
        $insert->bindValue(':authroId', $comment->getAuthorId(), \PDO::PARAM_INT);
        $insert->bindValue(':content', $comment->getContent(), \PDO::PARAM_STR);
        $insert->execute();

        return $this->getCommentById($this->db->lastInsertId());
    }

    /**
     * @param Comment $comment
     * @return Comment
     */
    public function updateComment(Comment $comment): Comment
    {
        $update = $this->db->prepare('UPDATE comments SET content = :content WHERE id = :id');
        $update->bindValue(':content', $comment->getContent(), \PDO::PARAM_STR);
        $update->bindValue(':id', $comment->getId(), \PDO::PARAM_INT);
        $update->execute();

        return $this->getCommentById($comment->getId());
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
}