<?php

  class BannersController extends CController {
    public $breadcrumbs;
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
      return array(
          'accessControl', // perform access control for CRUD operations
          'postOnly + delete', // we only allow deletion via POST request
      );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
      return array(
          array('allow', // allow admin user to perform 'admin' and 'delete' actions
              'users'=>array('admin'),
          ),
          array('deny',  // deny all users
              'users'=>array('*'),
          ),
      );
    }

    public function actionIndex() {
      $this->pageTitle = 'Banners rotate admin : index';
      $model = new Banners('search');
      if (Yii::app()->getRequest()->getPost('Banners')) {
        $model->attributes = Yii::app()->getRequest()->getPost('Banners');
//        print_r(Yii::app()->getRequest()->getPost('Banners'));
//        die();
      }

      if (Yii::app()->getRequest()->isPostRequest) {
        $this->renderPartial('_bannersListGrid', array('model' => $model));
      } else {
        $this->render('index', array('model' => $model));
      }
    }

    public function actionStat() {
    }

    public function actionDelete() {
      $id = Yii::app()->getRequest()->getQuery('id', NULL);
      $model = Banners::model()->findByPK($id);
      if ($model !== NULL) {
        $model->delete();
      }
      $this->redirect($this->createUrl("/banners/index"));
    }

    public function actionUpdate() {
      $this->pageTitle = 'Banners rotate admin : update';

      $id = Yii::app()->getRequest()->getQuery('id', NULL);

      if ($id !== NULL) {
        $model = Banners::model()->findbyPk($id); // загружаем данные по модели
        if ($model === NULL) // если данные в модели нет - вызываем ошибку
        {
          throw new CHttpException(404, 'The requested page does not exist.');
        }
      } else {
        $model = new Banners;
      }

      //$model->webFolder=Yii::app()->params['bannersWebFolder'];

      if (isset($_POST['Banners'])) // если к нам пришел POST
      {
        $model->oldFile = $model->bnrFile;
        $model->attributes = $_POST['Banners']; //присваиваем данные из POST в модель
        if ($model->validate()) //валидируем данные
        {
          $bnrFile = CUploadedFile::getInstance($model, 'bnrFile'); //а вдруг нам загрузили картинку к категории?
          if (is_object($bnrFile) && get_class($bnrFile) === 'CUploadedFile') //да, картинку нам все таки загрузили
          {
            $model->bnrFile = $bnrFile; // присваиваем данные
          } else {
            //картинку нам не дали, восстанавливаем старую картинку
            $model->bnrFile = $model->oldFile;
          }
          if ($model->save()) //сохраняем модель
          {
            $this->redirect($this->createUrl("/banners/index"));
          }
        }
      }

      $allocation = '';
      if ($model->pid) {
        $allocation = Alocations::model()->findByPk($model->pid);
        $allocation = $allocation?$allocation->name:'';
      }

      $this->render('update', array(
        'model' => $model,
        'update' => TRUE,
        'allocation' => $allocation
      ));
    }

    public function actionCopyBanner() {
      $id = Yii::app()->getRequest()->getQuery('id', NULL);
      $model = Banners::model()->findbyPk($id); // загружаем данные по модели
      if ($model !== FALSE) {
        $newBanner = new Banners();
        $newBanner->attributes = $model->attributes;
        unset($newBanner->id);
        $newBanner->bnrVisible = 0;
        $newBanner->save();
        $this->redirect($this->createUrl("/banners/index"));
      }
    }
  }
