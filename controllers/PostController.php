<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;
use app\models\Comment;
use app\models\Post;

class PostController extends Controller
{
    public function actionPopularPost(){
        $rs = Post::find()->orderBy('id')->all();
        
        foreach($rs as $r){
            $arr[] = array(
                'post_id' => $r->id,
                'post_title' => $r->title,
                'post_body' => $r->body,
                'total_number_of_comments' => count($r->comments),
            );
        }
        
        $sort = [];
        foreach ($arr as $key => $row){
            $sort[$key] = $row['total_number_of_comments'];
        }
        array_multisort($sort, SORT_DESC, $arr);
        
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => Response::FORMAT_JSON,
            'data' => $arr,
        ]);
    }
    
    public function actionSearchComments($keyword = ''){
        $rs = Comment::find()->where("name like '%$keyword%'")
            ->orWhere("email like '%$keyword%'")
            ->orWhere("body like '%$keyword%'")
            ->all();
        
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => Response::FORMAT_JSON,
            'data' => $rs,
        ]);
    }
}
