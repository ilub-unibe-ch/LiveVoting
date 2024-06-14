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
 * Class LiveVotingQuestionOption
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class LiveVotingQuestionOption
{
    private int $id = 0;
    private ?string $text = null;
    private int $type;
    private int $status = 1;
    private ?int $position = null;
    private ?int $correct_position = null;

    public function __construct(?array $data = null)
    {
        if ($data !== null) {
            $this->id = isset($data["id"]) ? (int) $data["id"] : 0;
            $this->text = $data["text"] ?? null;
            $this->type = (int) $data["type"];
            $this->status = isset($data["status"]) ? (int) $data["status"] : 1;
            $this->position = isset($data["position"]) ? (int) $data["position"] : null;
            $this->correct_position = isset($data["correct_position"]) ? (int) $data["correct_position"] : null;
        }
    }

    /**
     * @throws LiveVotingException
     */
    public static function loadOptionById(int $id) : ?LiveVotingQuestionOption
    {
        $option = null;

        $database = new LiveVotingDatabase();

        $result = $database->select("rep_robj_xlvo_option_n", array(
            "id" => $id
        ));

        if ($result && isset($result[0])) {
            $option = new LiveVotingQuestionOption($result[0]);
        }

        return $option;
    }

    public static function loadNewOption(int $type) : ?LiveVotingQuestionOption {
        $option = new LiveVotingQuestionOption();

        $option->setType($type);

        return $option;
    }

    /**
     * @throws LiveVotingException
     */
    public function delete(): void
    {
        $database = new LiveVotingDatabase();

        $database->delete("rep_robj_xlvo_option_n", array(
            "id" => $this->id
        ));
    }

    /**
     * @throws LiveVotingException
     */
    public function save(?int $voting_id) : int {
        $database = new LiveVotingDatabase();

        if ($this->id != 0) {
            $database->update("rep_robj_xlvo_option_n", array(
                "text" => $this->text,
                "type" => $this->type,
                "status" => $this->status,
                "position" => $this->position,
                "correct_position" => $this->correct_position
            ), array(
                "id" => $this->id
            ));
        } else if ($voting_id !== null && $voting_id != 0) {
            $this->id = $database->nextId("rep_robj_xlvo_option_n");

            $database->insert("rep_robj_xlvo_option_n", array(
                "id" => $this->id,
                "voting_id" => $voting_id,
                "text" => $this->text,
                "type" => $this->type,
                "status" => $this->status,
                "position" => $this->position,
                "correct_position" => $this->correct_position
            ));
        } else {
            throw new LiveVotingException("Invalid question id");
        }

        return $this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getCorrectPosition(): ?int
    {
        return $this->correct_position;
    }

    public function setCorrectPosition(?int $correct_position): void
    {
        $this->correct_position = $correct_position;
    }
}