<?php
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/classes/QuestionTypes/class.xlvoQuestionTypesGUI.php');

/**
 * Class xlvoSingleVoteGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy xlvoSingleVoteGUI: xlvoVoter2GUI
 */
class xlvoSingleVoteGUI extends xlvoQuestionTypesGUI {

	/**
	 * @description add JS to the HEAD
	 */
	public function initJS() {
		// TODO: Implement initJS() method.
	}


	/**
	 * @description Vote
	 */
	protected function submit() {
		$this->manager->vote($_GET['option_id']);
	}


	/**
	 * @return string
	 */
	public function getMobileHTML() {
		//		$answer_count = 64;
		//		$bars = new xlvoBarCollectionGUI();
		//		foreach ($this->voting->getVotingOptions() as $option) {
		//			$answer_count ++;
		//			$bars->addBar(new xlvoBarOptionGUI($this->voting, $option, (chr($answer_count))));
		//		}
		//
		//		return $bars->getHTML();

		$tpl = new ilTemplate('./Customizing/global/plugins/Services/Repository/RepositoryObject/LiveVoting/templates/default/QuestionTypes/SingleVote/tpl.single_vote.html', false, true);
		$answer_count = 64;
		foreach ($this->manager->getVoting()->getVotingOptions() as $xlvoOption) {
			$answer_count ++;
			$this->ctrl->setParameter($this, 'option_id', $xlvoOption->getId());
			$tpl->setCurrentBlock('option');
			$tpl->setVariable('TITLE', $xlvoOption->getText());
			$tpl->setVariable('LINK', $this->ctrl->getLinkTarget($this, self::CMD_SUBMIT));
			$tpl->setVariable('OPTION_LETTER', chr($answer_count));
			if($this->manager->hasUserVotedForOption($xlvoOption)) {
				$tpl->setVariable('VOTED', 'ja');
			}
			$tpl->parseCurrentBlock();
		}

		return $tpl->get();
	}
}
