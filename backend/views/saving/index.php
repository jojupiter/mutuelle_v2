<?php

use common\models\Exercise;
use common\models\Member;
use common\models\Session;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
/* @var $this View */

$this->title = 'Savings';
$this->params['breadcrumbs'][] = $this->title;
$month = ['January','February','March','April','May','June','July','August','September','October','November','December'];
$current_exercise_id = (new Query())->from('exercise')->max('id');
$current_exercise = Exercise::findOne(['id' => $current_exercise_id]);
$current_session_id = (new Query())->from('session')->where(['id_exercise' => $selected_exercise_id])->max('id');
$current_session = Session::findOne(['id' => $current_session_id]);
$exercises = (new Query())->from('exercise')->all();
$sessions_in_selected_exercise = (new Query())->select('id')->from('session')->where(['id_exercise' => $selected_exercise_id]);
$members_matricules = (new Query())->select('matricule_member')->from('saving')->where(['id_session'=>$sessions_in_selected_exercise])->distinct();
?>
<?php $user = \common\models\User::findOne(['id'=>Yii::$app->user->id]); ?>
<div class="saving-index">
            <div class="card" id="changeable" style="width:96%; margin: 0 auto;">
                <div class="card-header">
                    <h3 class="card-title">Savings Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                            <div class="form-group">
                                <label>Exercise</label><br>
                                <select class="form-control select2" id='exercice_combo' style="width: 20%;" tabindex="-1" aria-hidden="true" onchange="change_exercice(5)">
                                    <?php
                                    foreach ($exercises as $exercise) {
                                        if ($exercise['id'] == $selected_exercise_id) {
                                            echo '<option selected="selected" name ='.$exercise['id'].'>'.$month[$exercise['month']].' '.$exercise['year'].'</option>';                                  
                                        }else{
                                            echo '<option name ='.$exercise['id'].'>'.$month[$exercise['month']].' '.$exercise['year'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        
                    <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                               <th>Member</th>
                               <th>Total Savings (FCFA)</th>
                               <th style="text-align: center">Action</th>                               
                            </tr>
                        </thead>
                        <tbody>
                           <?php foreach($members_matricules->each() as  $matricule): ?>
                            <?php $member = Member::findOne(['matricule' => $matricule['matricule_member']]); ?>
                            <?php $amount = (new Query())->from('saving')->where(['matricule_member' => $member['matricule'] ,'id_session'=>$sessions_in_selected_exercise])->sum('amount'); ?>
                            <tr>
                               <td style="vertical-align: middle"><?= $member['firstname'].' '.$member['lastname'] ?></td>
                               <td style="vertical-align: middle"><?= number_format($amount,2,",",".") ?></td>
                    <td style="vertical-align: middle; text-align: center">
                        <a href="<?= Url::to(['saving/view', 'ide' => $selected_exercise_id , 'idm'=>$member['matricule']])?>" class="btn btn-app">
                  <i class="fa fa-eye"></i> Details
                                        </a>
                    </td>
                            </tr>
                            <?php endforeach; ?> 
                        </tbody>
                        <tfoot>
                            <tr>
                               <th>Member</th>
                               <th>Total Savings (FCFA)</th>
                               <th style="text-align: center;">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
        <?php if($current_exercise_id == $selected_exercise_id && $user->type == 1): ?>
                <div class="card-footer">
                    <p>
        <?= Html::a('Create Saving', ['saving/create'], ['class' => 'btn btn-success', 'id'=>'soumettre']) ?>
    </p>
                </div>
            </div>
            <!-- /.card -->
            <?php endif; ?>
         </div>
        <!-- /.col -->