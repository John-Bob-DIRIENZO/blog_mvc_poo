<?php


namespace Model;


use Entity\Post;

class PostManager extends BaseManager
{
    /**
     * @param int|null $number
     * @return array
     */
    public function getPosts(int $number = null): array
    {
        if ($number) {
            $query = $this->db->prepare('SELECT * FROM posts ORDER BY id DESC LIMIT :limit');
            $query->bindValue(':limit', $number, \PDO::PARAM_INT);
            $query->execute();
        } else {
            $query = $this->db->query('SELECT * FROM posts ORDER BY id DESC');
        }
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Post');

        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @return Post|bool
     */
    public function getPostById(int $id)
    {
        $query = $this->db->prepare('SELECT * FROM posts WHERE id = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Post');
        return $query->fetch();
    }

    /**
     * Returns an array of Post Objects
     * @param int $authorId
     * @return array
     */
    public function getPostsByAuthorId(int $authorId): array
    {
        $query = $this->db->prepare('SELECT * FROM posts WHERE authorId = :authorId');
        $query->bindValue(':authorId', $authorId, \PDO::PARAM_INT);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Entity\Post');
        return $query->fetchAll();
    }

    /**
     * @param Post $post
     * @return Post
     */
    public function addPost(Post $post): Post
    {
        $insert = $this->db->prepare('INSERT INTO posts (title, content, authorId) VALUES (:title, :content, :authorId)');
        $insert->bindValue(':title', htmlspecialchars($post->getTitle()), \PDO::PARAM_STR);
        $insert->bindValue(':content', nl2br(htmlspecialchars($post->getContent())), \PDO::PARAM_STR);
        $insert->bindValue(':authorId', $post->getAuthorId(), \PDO::PARAM_INT);
        $insert->execute();

        return $this->getPostById($this->db->lastInsertId());
    }

    /**
     * @param Post $post
     * @return Post
     */
    public function updatePost(Post $post): Post
    {
        $update = $this->db->prepare('UPDATE posts SET title = :title, content = :content WHERE id =:id');
        $update->bindValue(':title', htmlspecialchars($post->getTitle()), \PDO::PARAM_STR);
        $update->bindValue(':content', nl2br(htmlspecialchars($post->getContent())), \PDO::PARAM_STR);
        $update->bindValue(':id', $post->getId(), \PDO::PARAM_INT);
        $update->execute();

        return $this->getPostById($post->getId());
    }

    /**
     * Deletes a post and its attached comments
     * @param int $id
     * @return bool
     */
    public function deletePost(int $id): bool
    {
        $delete = $this->db->prepare('DELETE FROM posts WHERE id = :id');
        $delete->bindValue(':id', $id, \PDO::PARAM_INT);

        $manager = new CommentManager();
        $manager->deleteCommentsByPostId($id);

        return $delete->execute();
    }

    /**
     * Deletes all posts written by an author
     * and deletes all comments in those posts
     * @param int $authorId
     * @return bool
     */
    public function deletePostsByAuthorId(int $authorId): bool
    {
        $delete = $this->db->prepare('DELETE FROM posts WHERE authorId = :authorId');
        $delete->bindValue(':authorId', $authorId, \PDO::PARAM_INT);

        $postsToDelete = $this->getPostsByAuthorId($authorId);
        foreach ($postsToDelete as $postToDelete) {
            $this->deletePost($postToDelete->getId());
        }

        return $delete->execute();
    }
}