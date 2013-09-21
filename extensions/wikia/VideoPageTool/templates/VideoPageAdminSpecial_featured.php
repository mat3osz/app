<form class="WikiaForm vpt-form" method="post">

	<? for( $x = 1; $x <= count($videos); $x++ ): ?>

		<?
			$video = $videos[ $x ];
			$descriptionMaxLength = "200";
		?>

		<div class="form-box featured-video with-nav">
			<span class="count"><?= $x ?>.</span>
			<div class="input-group video-key-group">
				<button class="add-video-button"><?= wfMessage( 'videopagetool-button-add-video' )->text() ?></button>
				<p class="video-title <?= $video[ 'videoTitleClass' ] ?>"><?= $video[ 'videoTitle' ]  ?></p>
				<input type="hidden" name="videoKey[]" class="video-key" id="video-key-<?= $x ?>" value="<?= $video[ 'videoKey' ] ?>">
			</div>
			<div class="video-thumb"><?= $video[ 'videoThumb' ] ?></div>
			<div class="input-group">
				<input type="button" class="wmu-show" value="<?= wfMessage( 'videopagetool-button-add-thumbnail' )->plain() ?>">
				<p class="new-thumb <?= $video[ 'newThumbClass' ] ?>"><?= $video[ 'newThumbName' ]  ?></p>
				<input type="hidden" name="newThumbName[]" class="new-thumb" id="new-thumb-<?= $x ?>" value="<?= $video[ 'newThumbName' ] ?>">
			</div>
			<div class="input-group border">
				<label for="display-title-<?= $x ?>"><?= wfMessage( 'videopagetool-label-display-title' )->text() ?></label>
				<input class="display-title" id="display-title-<?= $x ?>" type="text" name="displayTitle[]" value="<?= $video[ 'displayTitle' ] ?>">
			</div>
			<div class="input-group">
				<label for="description-<?= $x ?>"><?= wfMessage( 'videopagetool-label-video-description' )->text() ?></label>
				<textarea maxlength="<?= $descriptionMaxLength ?>" class="description" id="description-<?= $x ?>" placeholder="<?= wfMessage( 'videopagetool-placeholder-video-description' )->text() ?>" name="description[]"><?= $video[ 'description' ] ?></textarea>
				<p class="hint"><?= wfMessage( 'videopagetool-hint-description-maxlength', $descriptionMaxLength )->plain() ?></p>
			</div>
			<button class="secondary navigation nav-up">
				<img class="chevron chevron-up" src="<?= $wg->BlankImgUrl ?>">
			</button>
			<button class="secondary navigation nav-down">
				<img class="chevron chevron-down" src="<?= $wg->BlankImgUrl ?>">
			</button>
		</div>

	<? endfor; ?>

	<input type="hidden" value="<?= $date ?>" name="date">
	<input type="hidden" value="<?= $language ?>" name="language">

	<div class="submits">
		<button type="submit"><?= wfMessage( 'videopagetool-button-save' )->text() ?></button>
		<button class="secondary reset"><?= wfMessage( 'videopagetool-button-clear' )->text() ?></button>
	</div>

</form>

