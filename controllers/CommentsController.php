<?php

class CommentsController extends Controller
{
    public function addComment()
    {
        $this->startSession();
        
        extract($_POST);
        if (isset($_SESSION['logged'])) {
            $user = $_SESSION['user'];

            if (strlen(trim($newId)) == 0) {
                return $this->view('notFound', [ "message" => "Post not found" ]);
            } else {
                $post = App::get('qBuilder')->selectById('news', $newId);
                if ($post['is_deleted']) {
                    return $this->view('notFound', [ "message" => "Post not found" ]);
                }
            }
            if (strlen(trim($content)) > 0) {

                App::get('qBuilder')->insert(
                    'news_comments',
                    'iisss',
                    [
                        'user' => $user['id'],
                        'new' => $newId,
                        'content' => trim($content),
                        'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
                        'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
                    ]
                );
            }
            return header("Location: /postDetails?id={$newId}");
        }
        return header('Location: /login');
    }

    public function getComments($newId, $itemsPerPage = 10, $page = 0)
    {
        $this->startSession();

        $comments = App::get('qBuilder')->selectWhere(
            'news_comments',
            'ii',
            [
                'new' => $newId,
                'is_deleted' => 0
            ],
            ($page - 1) * $itemsPerPage,
            $itemsPerPage
        );
        
        $comments = array_map(
            function($comment) {
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
                    $comment['user'] = App::get('qBuilder')->selectFieldsById(
                        'user',
                        [
                            "name",
                            "lastName",
                            "email"
                        ],
                        $comment['user']
                    );
                }
                return $comment;
            },
            $comments
        );

        return $comments;
    }

    public function editComment()
    {
        $this->startSession();
        
        if (!isset($_SESSION['logged'])) {
            return header('Location: /login');
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
                    'ss',
                    [
                        'content' => $content,
                        'updated_at' => (new DateTime())->format('Y-m-d H:i:s')
                    ]
                );
            }
            return header("Location: /postDetails?id={$comment['new']}");
        }
        return header("Location: /");        
    }

    public function deleteComment()
    {
        $this->startSession();
        if (!$_SESSION['logged']) {
            return header('Location: /login');
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
                    'i',
                    [
                        'is_deleted' => 1
                    ]
                );
            }
            return header("Location: /postDetails?id={$comment['new']}");
        }
        return header("Location: /");
    }
}