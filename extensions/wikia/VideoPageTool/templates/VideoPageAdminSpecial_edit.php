<noscript>This page is not supported with Javascript turned off.</noscript>

<?= $app->renderView(
	'VideoPageAdminSpecial',
	'Header',
	array(
		'section' => $section,
		'language' => $language,
		'savedBy' => $savedBy,
		'lastSavedOn' => $lastSavedOn,
		'publishDate' => $publishDate,
		'publishedBy' => $publishedBy,
		'programDate' => $programDate
	)
) ?>

<div class="WikiaGrid VPTForms">
	<div class="grid-2 alpha">
		<?= $app->renderView('LeftMenu',
			'Index',
			array('menuItems' => $leftMenuItems)
		) ?>
	</div>
	<div class="grid-4">

		<? if ( $result == 'error' ): ?>
			<p class="error" id="vpt-form-error"><?= $msg ?></p>
		<? elseif ( $result == 'ok' ): ?>
			<p class="success" id="vpt-form-success"><?= $msg ?></p>
		<? endif; ?>

		<?= $moduleView ?>
	</div>
	<div class="publish">
		<form class="WikiaForm" method="post" action="<?= $publishUrl ?>">
			<button type="submit" class="big" name="action" value="publish" <?= $publishButton ?>>
				<?= wfMessage( 'videopagetool-publish-button' )->escaped() ?>
			</button>
		</form>
	</div>
</div>
