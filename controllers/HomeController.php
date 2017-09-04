<?php namespace newsapp\controllers;

use newsapp\core\App;
use newsapp\core\Controller;

/**
 * Class that contains the news CRUD
 */
class HomeController extends Controller
{
    /**
     * Array of the columns which the news can be filtered
     *
     * @var array
     */
    private $filterFields = [
        'title' => 'Title',
        //'user' => 'User',
        'views' => 'Views Count',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At'
    ];

    /**
     * Array of the columns which the news can be ordered by
     *
     * @var array
     */
    private $oderByFields = [
        'title',
        'user',
        'views',
        'created_at',
        //'updated_at'
    ];

    /**
     * Displays the home page
     *
     * @return void
     */
    public function index() : void
    {
        $this->startSession();
        $title = 'Home';
        $searchFields = $this->filterFields;
        $filter = [];
        $order = 'created_at DESC';

        if (isset($_GET['s']) && isset($_GET['v'])) {
            if (array_key_exists($_GET['s'], $this->filterFields)) {
                $filter[$_GET['s']] = $_GET['v'];
            }

        }
        
        if (isset($_GET['o'])) {
            if (in_array($_GET['o'], $this->oderByFields)) {
                $order = $_GET['o'];
                
                if (isset($_GET['r'])) {
                    if ($_GET['r'] == 'true') {
                        $order .= ' DESC';
                    }
                }
            }
        }


        $pagination['count'] = App::get('qBuilder')->count(
            'news',
            [
                'is_deleted' => 0
            ],
            $filter
        );

        $pagination['itemsPerPage'] = 10;
        $pagination['linksCount'] = 5;
        if (isset($_GET['p'])) {
            $pagination['current'] = (int)$_GET['p'];
            if ($pagination['current'] <= 0) {
                $pagination['current'] = 1;
            }
        } else {
            $pagination['current'] = 1;
        }
        
        $news = App::get('qBuilder')->select(
            'news',
            [
                'is_deleted' => 0
            ],
            $filter,
            [],
            $order,
            ($pagination['current'] - 1) * $pagination['itemsPerPage'],
            $pagination['itemsPerPage']
        );
        $news = array_map(
            function ($new) {
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
        
        $this->view(
            'index',
            compact(
                'title',
                'news',
                'searchFields',
                'pagination'
            )
        );
    }

    /**
     * Displays a view with a list of news of the current user
     *
     * @return void
     */
    public function myPosts() : void
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            return;
        }
        $title = 'My Posts';
        $searchFields = $this->filterFields;
        $filter = [];
        $order = 'created_at DESC';

        if (isset($_GET['s']) && isset($_GET['v'])) {
            if (array_key_exists($_GET['s'], $this->filterFields)) {
                $filter[$_GET['s']] = $_GET['v'];
            }
        }

        if (isset($_GET['o'])) {
            if (in_array($_GET['o'], $this->oderByFields)) {
                $order = $_GET['o'];

                if (isset($_GET['r'])) {
                    if ($_GET['r'] == 'true') {
                        $order .= ' DESC';
                    }
                }
            }
        }


        $pagination['count'] = App::get('qBuilder')->count(
            'news',
            [
                'is_deleted' => 0, 'user' => $_SESSION['user']['id']
            ],
            $filter
        );

        $pagination['itemsPerPage'] = 9;
        $pagination['linksCount'] = 5;
        if (isset($_GET['p'])) {
            $pagination['current'] = (int)$_GET['p'];
            if ($pagination['current'] <= 0) {
                $pagination['current'] = 1;
            }
        } else {
            $pagination['current'] = 1;
        }

        $news = App::get('qBuilder')->select(
            'news',
            [
                'is_deleted' => 0, 'user' => $_SESSION['user']['id']
            ],
            $filter,
            [],
            $order,
            ($pagination['current'] - 1) * $pagination['itemsPerPage'],
            $pagination['itemsPerPage']
        );
           
        $news = array_map(
            function ($new) {
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
        
        $this->view(
            'myPosts',
            compact(
                'title',
                'news',
                'searchFields',
                'pagination'
            )
        );
    }

    /**
     * Displays a view with the details of a news
     *
     * @return void
     */
    public function postDetails() : void
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
                $post['content'] = explode('\n', nl2br($post['content']));
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
            if (isset($_GET['p'])) {
                $pagination['current'] = (int)$_GET['p'];
                if ($pagination['current'] <= 0) {
                    $pagination['current'] = 1;
                }
            } else {
                $pagination['current'] = 1;
            }

            $comments = (new CommentsController())->getComments(
                $post['id'],
                $pagination['itemsPerPage'],
                $pagination['current']
            );

            foreach ($comments as $key => $value) {
                $comments[$key]['content'] = nl2br($value['content']);
            }
            
            $qBuilder->update(
                'news',
                $post['id'],
                [
                    'views' => isset($post['views']) ? ++$post['views'] : 0
                ]
            );
            $title = $post['title'] ?? '';
            
            $this->view(
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

    /**
     * Displays the view with the form for creating a new post
     *
     * @return void
     */
    public function newPost() : void
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            return;
        }
        $this->view(
            'newPost',
            [
                'title' => 'New Post'
            ]
        );
    }

    /**
     * Saves a new post in the database
     *
     * @return void
     */
    public function addPost() : void
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            return;
        }
        
        $errorMessage = [];
        $title = 'New Post';
        extract($_POST);
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

            header("Location: /postDetails?id={$postId}");
            return;
        }
        $this->view(
            'newPost',
            compact(
                'title',
                'errorMessage',
                'postTitle',
                'content'
            )
        );
    }

    /**
     * Softly deletes a post
     *
     * @return void
     */
    public function deletePost() : void
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            return;
        }
        if (!isset($_POST['PostId'])) {
            $this->view(
                'notFound'
            );
            return;
        }
        $id = $_POST['PostId'];
        $qBuilder = App::get('qBuilder');

        $post = $qBuilder->selectById('news', $id);
        $title = $post['title'] ?? '';
        $owner = true;
        $exist = true;

        if (! count($post)) {
            $this->view(
                'notFound'
            );
            return;
        }
        
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

        $this->view(
            'postDeleted',
            compact(
                'title',
                'owner',
                'exist'
            )
        );
    }

    /**
     * Displays the view with the form for edit an existing post
     *
     * @return void
     */
    public function editPost() : void
    {
        $this->startSession();
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            return;
        }
        if (!isset($_GET['id'])) {
            $this->view(
                'notFound'
            );
            return;
        }
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

        $this->view(
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

    /**
     * Updates the given information of an existing post
     *
     * @return void
     */
    public function modifyPost() : void
    {
        $this->startSession();
        
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            return;
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
    
                    header("Location: /postDetails?id={$post['id']}");
                    return;
                }
            }
        } else {
            $title = 'Post not found';
            $exist = false;
        }

        $this->view(
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

    /**
     * Displays the default error page
     *
     * @return void
     */
    public function notFound() : void
    {
        $this->view(
            'notFound',
            [
                'title' => 'Page Not Found'
            ]
        );
    }
}
