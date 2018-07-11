<?php

use LiveVoting\Vote\xlvoVote;

/**
 * Class xlvoFreeInputResultGUI
 *
 * @author Oskar Truffer <ot@studer-raimann.ch>
 */
class xlvoFreeInputResultGUI extends xlvoResultGUI {

	/**
	 * @param xlvoVote[] $votes
	 *
	 * @return string
	 */
	public function getTextRepresentation(array $votes) {
		$strings = array();
		foreach ($votes as $vote) {
			$strings[] = str_replace([ "\r\n", "\r", "\n" ], " ", $vote->getFreeInput());
		}

		return implode(', ', $strings);
	}


	/**
	 * @param xlvoVote[] $votes
	 *
	 * @return string
	 */
	public function getAPIRepresentation(array $votes) {
		return $this->getTextRepresentation($votes);
	}
}
