<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
final class PostsManager
{
    use Nette\SmartObject;

    private const
        TABLE_NAME = 'posts',
        COLUMN_ID = 'id',
        COLUMN_TITLE = 'title',
        COLUMN_USER_ID = 'user_id',
        COLUMN_POST_ID = 'post_id',
        COLUMN_CONTENT = 'content',
        COLUMN_LIKE = 'like_reaction',
        COLUMN_DISLIKE = 'dislike_reaction';


    private Nette\Database\Explorer $database;

    private Passwords $passwords;


    public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    public function getTable()
    {
        return $this->database->table(self::TABLE_NAME);
    }

    public function getAllUser()
    {
        return $this->getTable()
            ->order('created_at DESC');
    }

    public function getAllNoLoginUser()
    {
        return $this->getTable()
            ->where("hide", 0)
            ->order('created_at DESC');
    }

    public function findPublishedArticles(int $limit, int $offset, int $type, $order, $sort): Nette\Database\ResultSet
    {

        $orderSql = "ORDER BY created_at ASC";

        if ($order != "" || $order != null) {
            $orderSql = 'ORDER BY ' . $order . " " . $sort;
        }

        if ($type == 1) {
            return $this->database->query('
			SELECT * FROM ' . self::TABLE_NAME . '
			WHERE hide= 0 and created_at < ?
			' . $orderSql . '
			LIMIT ?
			OFFSET ?',
                new \DateTime, $limit, $offset
            );

        } else {
            return $this->database->query('
			SELECT * FROM ' . self::TABLE_NAME . '
			WHERE created_at < ?
			' . $orderSql . '
			LIMIT ?
			OFFSET ?',
                new \DateTime, $limit, $offset
            );
        }
    }

    public function reactionOnPost($id, $reaction, $userId)
    {
        $reactionUser = $this->database->table("reaction")->where(self::COLUMN_USER_ID, $userId)->where(self::COLUMN_POST_ID, $id)->fetchAll();
        $reactionExist = 0;
        $reactionExistNew = 0;
        if (sizeof($reactionUser) > 0) {
            $reactionExist = $this->database->table("reaction")->where(self::COLUMN_USER_ID, $userId)->where(self::COLUMN_POST_ID, $id)->fetch();
            $this->database->table("reaction")->where(self::COLUMN_USER_ID, $userId)->where(self::COLUMN_POST_ID, $id)->update(['reaction' => $reaction]);
        } else {
            $this->database->table("reaction")->insert([
                self::COLUMN_USER_ID => $userId,
                self::COLUMN_POST_ID => $id,
                'reaction' => $reaction,
            ]);
            $reactionExistNew = 1;
        }
        $data = $this->getTable()->where(self::COLUMN_ID, $id)->fetch();
        if ($reaction == 1) {
            if ($reactionExistNew == 1) {
                $this->getTable()->where(self::COLUMN_ID, $id)->update([self::COLUMN_LIKE => intval($data[self::COLUMN_LIKE]) + 1]);

            } elseif ($reactionExist->reaction == 0) {
                if (intval($data[self::COLUMN_DISLIKE]) > 0) {
                    $this->getTable()->where(self::COLUMN_ID, $id)->update([self::COLUMN_DISLIKE => intval($data[self::COLUMN_DISLIKE]) - 1, self::COLUMN_LIKE => intval($data[self::COLUMN_LIKE]) + 1]);
                } else {
                    $this->getTable()->where(self::COLUMN_ID, $id)->update([self::COLUMN_LIKE => intval($data[self::COLUMN_LIKE]) + 1]);

                }
            }


        } else {
            if ($reactionExistNew == 1) {
                $this->getTable()->where(self::COLUMN_ID, $id)->update([self::COLUMN_DISLIKE => intval($data[self::COLUMN_DISLIKE]) + 1]);

            } elseif ($reactionExist->reaction == 1) {
                if (intval($data[self::COLUMN_LIKE]) > 0) {
                    $this->getTable()->where(self::COLUMN_ID, $id)->update([self::COLUMN_DISLIKE => intval($data[self::COLUMN_DISLIKE]) + 1, self::COLUMN_LIKE => intval($data[self::COLUMN_LIKE]) - 1]);
                }
                else{
                    $this->getTable()->where(self::COLUMN_ID, $id)->update([self::COLUMN_DISLIKE => intval($data[self::COLUMN_DISLIKE]) + 1]);

                }
            }

        }

    }


}