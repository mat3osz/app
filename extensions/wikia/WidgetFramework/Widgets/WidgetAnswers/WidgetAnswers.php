<?php
/**
 * @author Nick Sullivan nick at wikia inc.com
 * @author Inez Korczynski <inez@wikia-inc.com>
 * */
if(!defined('MEDIAWIKI')) {
	die(1);
}

global $wgWidgets, $wgAvailableAnswersLang;
$wgWidgets['WidgetAnswers'] = array(
	'callback' => 'WidgetAnswers',
	'title' => 'widget-title-answers',
	'desc' => 'widget-desc-answers',
    'closeable' => true,
    'editable' => false,
    'listable' => true,
    'languages' => $wgAvailableAnswersLang,
    'contentlang' => true,
);

function WidgetAnswers($id, $params) {
	global $IP, $wgTitle, $wgUser, $wgSitename, $wgContentNamespaces;

	wfProfileIn(__METHOD__);

	# TODO: should be handled directly in WidgetFramework, probably Draw/DrawOne method
	global $wgLanguageCode, $wgAvailableAnswersLang;
	if (!in_array($wgLanguageCode, $wgAvailableAnswersLang))        {
		return '';
	}

	global $wgEnableAnswersMonacoWidget;
	if (empty($wgEnableAnswersMonacoWidget)) {
		return '';
	}

	global $wgExtensionMessagesFiles;
	if( empty( $wgExtensionMessagesFiles['Answers'] ) ) {
		global $IP;
		$wgExtensionMessagesFiles['Answers'] = "$IP/../answers/Answers.i18n.php";
	}
	wfLoadExtensionMessages( 'Answers' );

	// This HTML for the Ask a Question is used for both logged in and logged out users
	// but in different place - top or the bottom of the widget
	$ask_a_question = htmlspecialchars(wfMsgForContent("ask_a_question"));
	$askform = <<<EOD
<form method="get" action="" onsubmit="return false" name="ask_form" class="ask_form">
	<input type="text" value="$ask_a_question" class="answers_ask_field"/>
</form>
EOD;

	if($wgUser->isLoggedIn()) {
		if(in_array('sysop', $wgUser->getGroups())) {
			$output = '<div class="widget_answers_note">'.wfMsgForContent('answers_widget_admin_note').'</div>';
		} else {
			$output = '<div class="widget_answers_note">'.wfMsgForContent('answers_widget_user_note').'</div>';
		}
	} else {
		$output = $askform;
	}

	$output .= '<div style="padding: 7px;"><b>'.wfMsgForContent('recent_asked_questions').'</b></div>';

	$apiparams = array(
		'smaxage'  =>  300,
		'maxage'   =>  300,
		'callback' => 'WidgetAnswers_load',
		'format'   => 'json',
		'action'   => 'query',
		'list'     => 'categoriesonanswers',
		'coatitle' =>  $wgSitename,
		'coalimit' => '5',
	);
#	if($wgUser->getOption('language') != 'en') { // waiting for international logic phase Future (v 2.0)
#		$domain = $wgUser->getOption('language');
#	} else {
		$domain = $wgLanguageCode;
#	}
	$url = 'http://'.$domain.'.answers.wikia.com/api.php?'.http_build_query($apiparams);

	$no_questions = wfMsgForContent("answers_widget_no_questions");
	if ( in_array( $wgTitle->getNamespace(), $wgContentNamespaces ) ) {
		$no_questions .= wfMsgForContent( 'answers_widget_no_questions_askabout' );
	}

	$output .= <<<EOD
<script type="text/javascript">/*<![CDATA[*/
var ask_a_question_msg = "{$ask_a_question}";
if(typeof WidgetAnswers_html == 'undefined') var WidgetAnswers_html = '';
var WidgetAnswers_url = '$url';
var node = jQuery('#{$id}_content').children('div:last');
if(WidgetAnswers_html == '') {
	jQuery.getScript(WidgetAnswers_url, function() {
		if(WidgetAnswers_html != '') node.append('<ul>' + WidgetAnswers_html + '</ul>');
		else node.html('{$no_questions}');
	});
} else node.append('<ul>' + WidgetAnswers_html + '</ul>');
/*]]>*/</script>
EOD;

	if($wgUser->isLoggedIn()) {
		$output .= $askform;
	} else {
		$output .= '<div class="widget_answers_note">'.wfMsgForContent('answers_widget_anon_note').'</div>';
	}

	wfProfileOut(__METHOD__);
	return $output;
}
