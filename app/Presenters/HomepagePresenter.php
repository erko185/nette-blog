<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private Model\PostsManager $postsManager;
    /**
     * @var string
     * @persistent
     */
    public $order;

    /**
     * @var string
     * @persistent
     */
    public $sort;
    /**
     * @var integer
     * @persistent
     */
    public $page = 1;


    private $like = 'like';

    public function __construct(Model\PostsManager $postsManager)
    {
        $this->postsManager = $postsManager;
    }

    public function actionDefault($page, $order, $sort)
    {
        $this->order = (string)$order;
        $this->sort = $sort;
        $this->page = $page;
    }

    public function renderDefault(): void
    {

        if ($this->sort == 'asc') {
            $sortValue = 'desc';
        } else {
            $sortValue = 'asc';
        }


        if (!$this->getUser()->isLoggedIn()) {
            $getAll = $this->postsManager->getAllNoLoginUser();
            $type = 1;
        } else {
            $getAll = $this->postsManager->getAllUser();
            $type = 0;

        }
        $articlesCount = sizeof($getAll);

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($articlesCount);
        $paginator->setItemsPerPage(3);
        $paginator->setPage($this->page);
        $this->template->posts = $getAll;
        $articles = $this->postsManager->findPublishedArticles($paginator->getLength(), $paginator->getOffset(), $type, $this->order, $this->sort);

        $this->template->articles = $articles;
        $this->template->paginator = $paginator;
        $this->template->order = $this->order;
        $this->template->sort = $this->sort;
        $this->template->sortValue = $sortValue;
        $this->template->page = $this->page;
    }

    public function handleLike($postId, $value)
    {
        if ($this->isAjax()) {

            $this->postsManager->reactionOnPost($postId, $value, $this->getUser()->getId());
            $this->redrawControl('articles');
        }

    }


}
