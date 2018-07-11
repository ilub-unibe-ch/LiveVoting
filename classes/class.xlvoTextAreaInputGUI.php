<?php

/**
 * Class xlvoTextAreaInputGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class xlvoTextAreaInputGUI extends ilTextAreaInputGUI {

	/**
	 * @var string
	 */
	protected $inline_style = '';
	/**
	 * @var ilLiveVotingPlugin
	 */
	protected $pl;
	/**
	 * @var int
	 */
	protected $maxlength = 1000;


	/**
	 * @param string $a_title
	 * @param string $a_postvar
	 */
	public function __construct($a_title = "", $a_postvar = "") {
		$this->pl = ilLiveVotingPlugin::getInstance();

		parent::__construct($a_title, $a_postvar);
	}


	/**
	 *
	 */
	public function customPrepare() {
		$this->addPlugin('latex');
		$this->addButton('latex');
		$this->addButton('pastelatex');
		$this->setUseRte(true);
		$this->setRteTags(array(
			'p',
			'br',
			'b',
			'span',
		));
		$this->usePurifier(true);
		$this->disableButtons(array(
			'charmap',
			'undo',
			'redo',
			'justifyleft',
			'justifycenter',
			'justifyright',
			'justifyfull',
			'anchor',
			'fullscreen',
			'cut',
			'copy',
			'paste',
			'pastetext',
			'formatselect',
		));
	}


	/**
	 * @return string
	 */
	public function render() {
		$tpl = $this->pl->getTemplate('default/tpl.text_area_helper.html', false, false);
		$this->insert($tpl);
		$tpl->setVariable('INLINE_STYLE', $this->getInlineStyle());

		return $tpl->get();
	}


	/**
	 * @return string
	 */
	public function getInlineStyle() {
		return $this->inline_style;
	}


	/**
	 * @param string $inline_style
	 */
	public function setInlineStyle($inline_style) {
		$this->inline_style = $inline_style;
	}


	/**
	 * @return int
	 */
	public function getMaxlength() {
		return $this->maxlength;
	}


	/**
	 * @param int $maxlength
	 */
	public function setMaxlength($maxlength) {
		$this->maxlength = $maxlength;
	}
}
