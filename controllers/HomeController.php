<?php

class HomeController extends Controller
{

    protected $filterFields = [
        'title' => 'Title',
        //'user' => 'User',
        'views' => 'Views Count',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At'
    ];

    public function index()
    {
        $this->startSession();
        $title = 'Home';
        $searchFields = array_values($this->filterFields);
        $filter = [ 'is_deleted' => 0 ];
        if (isset($_GET['searchBy'])) {
            $filter[array_keys($this->filterFields)[$_GET['searchBy']]]= $_GET['value'];
        }

        $pagination['count'] = App::get('qBuilder')->count(
            'news',
            [],
            $filter
        );

        $pagination['itemsPerPage'] = 3;
        $pagination['linksCount'] = 5;
        $pagination['current'] = abs($_GET['page'] ?? 1);
        
        $news = App::get('qBuilder')->select(
            'news',
            [],
            $filter,
            [],
            'created_at DESC',
            ($pagination['current'] - 1) * $pagination['itemsPerPage'],
            $pagination['itemsPerPage']
        );
        $news = array_map(
            function($new) {
                if (isset($new['user'])) {
                    $new['user'] = App::get('qBuilder')->selectById(
                        'user',
                        $new['user'],
                        [
                            'name',
                            'lastName',
                            'email'
                        ]
                    );
                }
                return $new;
            },
            $news
        );
        
        return $this->view(
            'index',
            compact(
                'title',
                'news',
                'searchFields',
                'pagination'
            )
        );
    }

    public function myPosts()
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            return header('Location: /login');
        }
        $title = 'My Posts';
        $searchFields = array_values($this->filterFields);
        $filter = [];
        if (isset($_GET['searchBy'])) {
            $filter[array_keys($this->filterFields)[$_GET['searchBy']]]= $_GET['value'];
        }

        $pagination['count'] = App::get('qBuilder')->count(
            'news',
            [
                'is_deleted' => 0, 'user' => $_SESSION['user']['id']
            ],
            $filter
        );

        $pagination['itemsPerPage'] = 3;
        $pagination['linksCount'] = 5;
        $pagination['current'] = abs($_GET['page'] ?? 1);

        $news = App::get('qBuilder')->select(
            'news',
            [
                'is_deleted' => 0, 'user' => $_SESSION['user']['id']
            ],
            $filter,
            [],
            'created_at DESC',
            ($pagination['current'] - 1) * $pagination['itemsPerPage'],
            $pagination['itemsPerPage']
        );
           
        $news = array_map(
            function($new) {
                if (isset($new['user'])) {
                    $new['user'] = App::get('qBuilder')->selectById(
                        'user',
                        $new['user'],
                        [
                            'name',
                            'lastName',
                            'email'
                        ]
                    );
                }
                return $new;
            },
            $news
        );
        
        return $this->view(
            'myPosts',
            compact(
                'title',
                'news',
                'searchFields',
                'pagination'
            )
        );
    }

    public function postDetails()
    {
        $this->startSession();
        if (isset($_GET['id'])) {
            $owner = false;
            $qBuilder = App::get('qBuilder');

            $post = $qBuilder->selectById('news', $_GET['id']);
            if ($post['is_deleted'] || !count($post)) {
                header('Location: /notFound');                
            }

            if (isset($post['user'])) {
                if (isset($_SESSION['logged'])) {
                    if ($_SESSION['user']['id'] === $post['user']) {
                        $owner = true;
                    }
                }
                $post['user'] = $qBuilder->selectById(
                    'user',
                    $post['user'],
                    [
                        'name',
                        'lastName',
                        'email'
                    ]
                );
            }
            
            if (isset($post['content'])) {
                $post['content'] = explode('\n',$post['content']);
            }

            $pagination['count'] = App::get('qBuilder')->count(
                'news_comments',
                [
                    'new' => $post['id'],
                    'is_deleted' => 0
                ]
            );

            $pagination['itemsPerPage'] = 3;
            $pagination['linksCount'] = 5;
            $pagination['current'] = abs($_GET['page'] ?? 1);

            $comments = (new CommentsController)->getComments($post['id'], $pagination['itemsPerPage'], $pagination['current']);
            
            $qBuilder->update(
                'news',
                $post['id'],
                [
                    'views' => isset($post['views']) ? ++$post['views'] : 0
                ]
            );
            $title = $post['title'] ?? '';
            
            return $this->view(
                'postDetails',
                compact(
                    'post',
                    'title',
                    'owner',
                    'comments',
                    'pagination'
                )
            );
        }
    }

    public function newPost()
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
        } else {
            return $this->view(
                'newPost',
                [
                    'title' => 'New Post'
                ]
            );
        }
    }

    public function addPost()
    {
        $this->startSession();
        
        $errorMessage = [];
        $title = 'New Post';
        extract($_POST);
        if (isset($_SESSION['logged'])) {
            $user = $_SESSION['user'];
        
            if (strlen(trim($postTitle)) < 5) {
                $errorMessage[] = 'The title must have at least 5 charcters.';
            }
            if (strlen(trim($content)) == 0) {
                $errorMessage[] = 'The content is required.';
            }
            if (count($errorMessage) == 0) {

                $postId = App::get('qBuilder')->insert(
                    'news',
                    [
                        'title' => $postTitle,
                        'content' => $content,
                        'user' => $user['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                );

                return header("Location: /postDetails?id={$postId}");
            }
            return $this->view(
                'newPost',
                compact(
                    'title',
                    'errorMessage',
                    'postTitle',
                    'content'
                )
            );
            
        }
        return header('Location: /login');
    }

    public function deletePost()
    {
        $this->startSession();
        if (!$_SESSION['logged']) {
            return header('Location: /login');
        }
        if (isset($_POST['PostId'])) {
            $id = $_POST['PostId'];
            $qBuilder = App::get('qBuilder');

            $post = $qBuilder->selectById('news', $id);
            $title = $post['title'] ?? '';
            $owner = true;
            $exist = true;
            
            if (! $post['is_deleted']) {
                if ($post['user'] === $_SESSION['user']['id']) {
                    $qBuilder->update(
                        'news',
                        $post['id'],
                        [
                            'is_deleted' => 1,
                            'updated_at' => date('Y-m-d H:i:s')                            
                        ]
                    );
                } else {
                    $owner = false;
                }
            } else {
                $title = 'Deleted Post';                
                $exist = false;
            }

            return $this->view(
                'postDeleted',
                compact(
                    'title',
                    'owner',
                    'exist'
                )
            );
        }
        return $this->view(
            'notFound'
        );
    }

    public function editPost()
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            return header('Location: /login');
        }
        if (isset($_GET['id'])) {
            $qBuilder = App::get('qBuilder');

            $post = $qBuilder->selectById('news', $_GET['id']);
            $id = $post['id'];
            $title = 'Edit Post';
            $postTitle = $post['title'] ?? '';
            $content = $post['content'] ?? '';
            $owner = true;
            $exist = true;
            if (! $post['is_deleted']) {
                if ($post['user'] !== $_SESSION['user']['id']) {
                    $owner = false;
                }
            } else {
                $title = 'Post not found';
                $exist = false;
            }

            return $this->view(
                'editPost',
                compact(
                    'title',
                    'owner',
                    'exist',
                    'postTitle',
                    'content',
                    'id'
                )
            );
        }
    }

    public function modifyPost()
    {
        $this->startSession();
        
        if (!isset($_SESSION['logged'])) {
            return header('Location: /login');
        }
        
        $qBuilder = App::get('qBuilder');
        
        $errorMessage = [];
        $title = 'Edit Post';
        extract($_POST);
        $post = $qBuilder->selectById('news', $id);
        $owner = true;
        $exist = true;
        if (! $post['is_deleted']) {
            if ($post['user'] != $_SESSION['user']['id']) {
                $owner = false;
            } else {
                if (strlen(trim($postTitle)) < 5) {
                    $errorMessage[] = 'The title must have at least 5 charcters.';
                }
                if (strlen(trim($content)) == 0) {
                    $errorMessage[] = 'The content is required.';
                }
                if (count($errorMessage) == 0) {
    
                    $qBuilder->update(
                        'news',
                        $post['id'],
                        [
                            'title' => $postTitle,
                            'content' => $content,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]
                    );
    
                    return header("Location: /postDetails?id={$post['id']}");
                }
            }
        } else {
            $title = 'Post not found';
            $exist = false;
        }

        return $this->view(
            'editPost',
            compact(
                'title',
                'owner',
                'exist',
                'postTitle',
                'content',
                'id',
                'errorMessage'
            )
        );
    }

    public function notFound()
    {
        return $this->view(
            'notFound',
            [
                'title' => 'Page Not Found'
            ]
        );
    }
}
