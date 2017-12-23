<div class="logo">
	<img src="<? echo $this->getAssetsUrl(); ?>/img/logo.png">
</div>
<? if(Yii::app()->user->hasFlash('RECOVERY_SUCCESS')) { ?>

	<div class="message"><? echo Yii::app()->user->getFlash('RECOVERY_SUCCESS') ?></div>

<? } else { ?>

	<form method="POST" class="form_recovery">
		<input type="text" name="Users[new_password]" placeHolder="Enter new password">
		<input type="submit" class="btn red" value="Reset password">
	</form>

<? } ?>