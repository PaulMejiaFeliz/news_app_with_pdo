<?php namespace newsapp\controllers;

use newsapp\core\App;
use newsapp\core\Controller;

/**
 * Class with the comments CRUD
 */
class CommentsController extends Controller
{
    /**
     * Creates a new comment in a news
     *
     * @return void
     */
    public function addComment() : void
    {
        $this->startSession();
        
        extract($_POST);
        if (isset($_SESSION['logged'])) {
            $user = $_SESSION['user'];

            if (strlen(trim($newId)) == 0) {
                $this->view('notFound', [ 'message' => 'Post not found' ]);
                return;
            } else {
                $post = App::get('qBuilder')->selectById('news', $newId);
                if ($post['is_deleted']) {
                    $this->view('notFound', [ 'message' => 'Post not found' ]);
                    return;
                }
            }
            if (strlen(trim($content)) > 0) {

                App::get('qBuilder')->insert(
                    'news_comments',
                    [
                        'user' => $user['id'],
                        'new' => $newId,
                        'content' => trim($content),
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
            header("Location: /postDetails?id={$newId}");
            return;
        }
        header('Location: /login');
    }

    /**
     * Modifies an existing comment
     *
     * @return void
     */
    public function editComment() : void
    {
        $this->startSession();
        
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            return;
        }
        
        if (isset($_POST['commentId'])) {
            $qBuilder = App::get('qBuilder');
            extract($_POST);
            $comment = $qBuilder->selectById('news_comments', $commentId);
            
            if (! $comment['is_deleted']
                && strlen(trim($content)) > 0
                && $comment['user'] == $_SESSION['user']['id']
            ) {
                $qBuilder->update(
                    'news_comments',
                    $comment['id'],
                    [
                        'content' => $content,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
            header("Location: /postDetails?id={$comment['new']}");
            return;
        }
        header('Location: /');
    }

    /**
     * Softly deletes a comment
     *
     * @return void
     */
    public function deleteComment() : void
    {
        $this->startSession();
        if (!$_SESSION['logged']) {
            header('Location: /login');
            return;
        }
        if (isset($_POST['commentId'])) {
            $qBuilder = App::get('qBuilder');

            $comment = $qBuilder->selectById('news_comments', $_POST['commentId']);
            
            if (! $comment['is_deleted']
                && $comment['user'] === $_SESSION['user']['id']
            ) {
                $qBuilder->update(
                    'news_comments',
                    $comment['id'],
                    [
                        'is_deleted' => 1,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
            header("Location: /postDetails?id={$comment['new']}");
            return;
        }
        header('Location: /');
    }

    /**
     * Retrieves the comments of a news
     *
     * @param int $newId Id of the news
     * @param int $itemsPerPage Count of comments that will be returned for the current page
     * @param int $page number of the current page
     * @return array array of comments
     */
    public function getComments(int $newId, int $itemsPerPage = 10, int $page = 0) : array
    {
        $this->startSession();

        $comments = App::get('qBuilder')->select(
            'news_comments',
            [
                'new' => $newId,
                'is_deleted' => 0
            ],
            [],
            [],
            'created_at DESC',
            ($page - 1) * $itemsPerPage,
            $itemsPerPage
        );
        
        $comments = array_map(
            function ($comment) {
                if (isset($comment['user'])) {
                    if (isset($_SESSION['logged'])) {
                        if ($comment['user'] == $_SESSION['user']['id']) {
                            $comment['owner'] = true;
                        } else {
                            $comment['owner'] = false;
                        }
                    } else {
                        $comment['owner'] = false;
                    }
                    $comment['user'] = App::get('qBuilder')->selectById(
                        'user',
                        $comment['user'],
                        [
                            'name',
                            'lastName',
                            'email'
                        ]
                    );
                }
                return $comment;
            },
            $comments
        );

        return $comments;
    }
}
