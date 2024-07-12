<?php
declare(strict_types=1);
/**
 * This file is part of the LiveVoting Repository Object plugin for ILIAS.
 * This plugin allows to create real time votings within ILIAS.
 *
 * The LiveVoting Repository Object plugin for ILIAS is open-source and licensed under GPL-3.0.
 * For license details, visit https://www.gnu.org/licenses/gpl-3.0.en.html.
 *
 * To report bugs or participate in discussions, visit the Mantis system and filter by
 * the category "LiveVoting" at https://mantis.ilias.de.
 *
 * More information and source code are available at:
 * https://github.com/surlabs/LiveVoting
 *
 * If you need support, please contact the maintainer of this software at:
 * info@surlabs.es
 *
 */



/**
 * Class LiveVotingPrioritiesPlayerGUI
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 * @ilCtrl_isCalledBy LiveVotingCorrectOrderPlayerGUI: ilUIPluginRouterGUI, LiveVotingPlayerGUI
 * @ilCtrl_Calls LiveVotingCorrectOrderPlayerGUI: LiveVotingPlayerGUI, ilUIPluginRouterGUI
 */
class LiveVotingPrioritiesPlayerGUI extends LiveVotingCorrectOrderPlayerGUI
{
    /**
     * @return bool
     */
    protected function isRandomizeOptions(): bool
    {
        return false;
    }


    /**
     * @return string
     */
    public function getMobileHTML(): string
    {
        return $this->getFormContent() . xlvoJs::getInstance()->name(xlvoQuestionTypes::FREE_ORDER)->category('QuestionTypes')->getRunCode();
    }


    /**
     * @param bool $current
     */
    public function initJS($current = false)
    {
        xlvoJs::getInstance()->api($this)->name(xlvoQuestionTypes::FREE_ORDER)->category('QuestionTypes')
            ->addLibToHeader('jquery.ui.touch-punch.min.js')->init();
    }


    /**
     * @return array
     */
    public function getButtonInstances()
    {
        if (!$this->manager->getPlayer()->isShowResults()) {
            return array();
        }
        $states = $this->getButtonsStates();
        $b = ilLinkButton::getInstance();
        $b->setId(self::BUTTON_TOTTLE_DISPLAY_CORRECT_ORDER);
        if ( array_key_exists(self::BUTTON_TOTTLE_DISPLAY_CORRECT_ORDER,$states) && $states[self::BUTTON_TOTTLE_DISPLAY_CORRECT_ORDER]) {
            $b->setCaption(GlyphGUI::get('align-left'), false);
        } else {
            $b->setCaption(GlyphGUI::get('sort-by-attributes-alt'), false);
        }

        //		$t = ilLinkButton::getInstance();
        //		$t->setId(self::BUTTON_TOGGLE_PERCENTAGE);
        //		if ($states[self::BUTTON_TOGGLE_PERCENTAGE]) {
        //			$t->setCaption(' %', false);
        //		} else {
        //			$t->setCaption(GlyphGUI::get('user'), false);
        //		}

        return array($b);
    }

}