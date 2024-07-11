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
namespace LiveVoting\UI\QuestionsResults;

use ilLiveVotingPlugin;
use ilTemplateException;
use LiveVoting\platform\LiveVotingException;
use LiveVoting\questions\LiveVotingQuestionOption;
use LiveVoting\UI\Voting\Bar\LiveVotingBarCollectionUI;
use LiveVoting\UI\Voting\Bar\LiveVotingBarPercentageUI;
use LiveVoting\votings\LiveVotingVote;

class LiveVotingInputCorrectOrder extends LiveVotingSingleVoteResultsUI
{
    /**
     * @return string
     * @throws LiveVotingException|ilTemplateException
     */
    public function getHTML(): string
    {
        $bars = new LiveVotingBarCollectionUI();

        $correct_order = array();
        foreach ($this->player->getActiveVotingObject()->getOptions() as $xlvoOption) {
            $correct_order[(int) $xlvoOption->getCorrectPosition()] = $xlvoOption;
            $correct_order_ids[(int) $xlvoOption->getCorrectPosition()] = $xlvoOption->getId();
        };
        ksort($correct_order);
        ksort($correct_order_ids);
        $correct_order_json = json_encode(array_values($correct_order_ids));

        $votes = LiveVotingVote::getVotesOfQuestion($this->player->getId(), $this->player->getRoundId());
        $correct_votes = 0;
        $wrong_votes = 0;
        foreach ($votes as $xlvoVote) {
            if ($xlvoVote->getFreeInput() == $correct_order_json) {
                $correct_votes++;
            } else {
                $wrong_votes++;
            }
        }

        $correct_option = new LiveVotingQuestionOption();
        $correct_option->setText(ilLiveVotingPlugin::getInstance()->txt('correct'));
        $bar = new LiveVotingBarPercentageUI();
        $bar->setTitle($correct_option->getText());
        $bar->setVotes($correct_votes);
        $bar->setMaxVotes(LiveVotingVote::countVoters($this->player->getId(), $this->player->getRoundId()));
        $bar->setShowInPercent(!$this->isShowAbsolute());

        $bars->addBar($bar);

        $wrong_option = new LiveVotingQuestionOption();
        $wrong_option->setText(ilLiveVotingPlugin::getInstance()->txt('wrong'));

        $bar = new LiveVotingBarPercentageUI();
        $bar->setMaxVotes(LiveVotingVote::countVoters($this->player->getId(), $this->player->getRoundId()));
        $bar->setTitle($wrong_option->getText());
        $bar->setVotes($wrong_votes);
        $bar->setShowInPercent(!$this->isShowAbsolute());

        $bars->addBar($bar);

        $bars->setShowTotalVotes(true);
        $bars->setTotalVotes(LiveVotingVote::countVotes($this->player->getId(), $this->player->getRoundId()));
        if ($this->isShowCorrectOrder()) {
            $solution_html = ilLiveVotingPlugin::getInstance()->txt('correct_solution') . '<br>';
            /**
             * @var LiveVotingQuestionOption $item
             */
            foreach ($correct_order as $item) {
                $solution_html .= ' <p><div class="xlvo-option"><span class="label label-primary xlvo-option_letter">' . $item->getCipher()
                    . '</span> <span class="option_text">' . $item->getText() . '</span></div></p>';
            }
            $bars->addSolution($solution_html);
        }

        return $bars->getHTML();
    }


    /**
     * @return bool
     */
    protected function isShowCorrectOrder(): bool
    {
        $states = $this->getButtonsStates();

        return ((bool) (array_key_exists('display_correct_order',$states) && $states['display_correct_order']) && $this->player->isShowResults());
    }


    /**
     * @return bool
     */
    protected function isShowAbsolute(): bool
    {
        $states = $this->getButtonsStates();

        return ($this->player->isShowResults() && (bool) (array_key_exists('toggle_percentage',$states) && $states['toggle_percentage']));
    }

}
