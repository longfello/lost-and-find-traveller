<?php

class FrontController extends Controller {

    public $errorAction;
    public $breadcrumbs = array();

    function __construct() {
        parent::__construct($this->id, $this->module);
        //check if is set default layout for module
        $defaultLayout = Yii::app()->getModule('cms')->defaultLayout;
        if ($defaultLayout)
            $this->layout = $defaultLayout;
    }

    public function actionInitCms($url) {
        Yii::import('application.modules.cms.models.*');
        $page = Cms::model()->find('url=:url', array(':url' => $url));
        $this->processCms($page);

    }

    function processCms($page) {
        $user = Yii::app()->user;

        if ($page->access_level == Cms::AUTH_ONLY && $user->getIsGuest()) {
            $this->redirect($user->loginUrl);
        }

        if ($page->layout)
            $this->layout = $page->layout;

        if ($keywords = trim($page->keywords))
            Yii::app()->clientScript->registerMetaTag($keywords, 'keywords');

        if ($description = trim($page->description))
            Yii::app()->clientScript->registerMetaTag($description, 'description');

        if ($title = trim($page->title))
            $this->pageTitle = $title;

        switch ($page->type) {
            case Cms::PAGESET:
                if ($page->overview_page || $page->id == 1) {
                    $content = $this->renderPageContent($page);
                } else {
                    $firstChild = Cms::model()->find('parent_id=:parent_id', array(':parent_id' => $page->id));
                    if (!$firstChild) {
                        $this->showError();
                    }
                    $this->redirect($firstChild->url);
                }
                break;
            case Cms::PAGE:
                $content = $this->renderPageContent($page);
                break;
            case Cms::LINK:
                $this->redirect($page->url);
                break;
        }

        $section = $page->section ? '/' . $page->section : '/cms/default';
        $this->render($section, array('page' => $page, 'content' => $content));
    }

    function renderPageContent($page) {
        if ($page->subsection) {
            $content = $this->renderPartial($page->subsection, array('page' => $page, 'content' => $page->content), true);
        }
        return $content;
    }

    public static function renderHomePage() {
        $cmsHandler = new CmsHandler;
        $page = Cms::model()->findByPk(1);
        $cmsHandler->processCms($page);
    }

}

?>
