<?php

namespace LiveVoting\QuestionTypes\FreeOrder;

use ilException;
use ilFormPropertyGUI;
use LiveVoting\Exceptions\xlvoSubFormGUIHandleFieldException;
use LiveVoting\Option\xlvoOption;
use LiveVoting\QuestionTypes\xlvoSubFormGUI;
use srag\CustomInputGUIs\LiveVoting\MultiLineNewInputGUI\MultiLineNewInputGUI;
use srag\CustomInputGUIs\LiveVoting\TextInputGUI\TextInputGUI;
use srag\CustomInputGUIs\LiveVoting\HiddenInputGUI\HiddenInputGUI;
use arFactory;
use ilTextInputGUI;


/**
 * Class xlvoFreeOrderSubFormGUI
 *
 * @package LiveVoting\QuestionTypes\FreeOrder
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class xlvoFreeOrderSubFormGUI extends xlvoSubFormGUI
{

    const F_OPTIONS = 'options';
    const F_TEXT = 'text';
    const F_ID = 'id';
    const F_POSITION = 'position';
    const F_WEIGHT = 'weight';
    /**
     * @var xlvoOption[]
     */
    protected $options = array();


    /**
     *
     */
    protected function initFormElements()
    {
/*        $xlvoMultiLineInputGUI = new MultiLineNewInputGUI($this->txt(self::F_OPTIONS), self::F_OPTIONS);
        $xlvoMultiLineInputGUI->setShowInputLabel(false);
        $xlvoMultiLineInputGUI->setShowSort(true);

        $h = new HiddenInputGUI(self::F_ID);
        $xlvoMultiLineInputGUI->addInput($h);

        $te = new TextInputGUI($this->txt('option_text'), self::F_TEXT);

        $xlvoMultiLineInputGUI->addInput($te);

        $this->addFormElement($xlvoMultiLineInputGUI);*/

        $xlvoMultiLineInputGUI = new MultiLineNewInputGUI($this->txt(self::F_OPTIONS), self::F_OPTIONS);
        $xlvoMultiLineInputGUI->setShowInputLabel(false);
        $xlvoMultiLineInputGUI->setShowSort(true);

        $h = new HiddenInputGUI(self::F_ID);
        $xlvoMultiLineInputGUI->addInput($h);

        $xlvoMultiLineInputGUI = new ilTextInputGUI($this->txt(self::F_OPTIONS), self::F_OPTIONS);
        $xlvoMultiLineInputGUI->setMulti(true,true,true);

        $this->addFormElement($xlvoMultiLineInputGUI);

    }


    /**
     * @param ilFormPropertyGUI $element
     * @param string|array      $value
     *
     * @throws xlvoSubFormGUIHandleFieldException|ilException
     */
    protected function handleField(ilFormPropertyGUI $element, $value)
    {
        switch ($element->getPostVar()) {
            case self::F_OPTIONS:
                $pos = 1;
                foreach ($value as $item=>$id) {
                    /**
                     * @var xlvoOption $xlvoOption
                     */

                    if(($id === null || trim($id) === '')){
                        $class_name = xlvoOption::class;
                        $xlvoOption = arFactory::getInstance($class_name);
                        $xlvoOption->storeObjectToCache();
                    }
                    else{
                        $xlvoOption = xlvoOption::findOrGetInstance($id);
                    }

                    $xlvoOption->setText($element->stripSlashesAddSpaceFallback($id));
                    $xlvoOption->setStatus(xlvoOption::STAT_ACTIVE);
                    $xlvoOption->setVotingId($this->getXlvoVoting()->getId());
                    $xlvoOption->setPosition($pos);
                    //$xlvoOption->setCorrectPosition($item[self::F_WEIGHT]);
                    $xlvoOption->setCorrectPosition($pos);
                    $xlvoOption->setType($this->getXlvoVoting()->getVotingType());
                    $this->options[] = $xlvoOption;
                    $pos++;
                }
                break;
            default:
                throw new ilException('Unknown element can not get the value.');
        }
    }


    /**
     * @param ilFormPropertyGUI $element
     *
     * @return string|int|float|array
     * @throws ilException
     */
    protected function getFieldValue(ilFormPropertyGUI $element)
    {

        switch ($element->getPostVar()) {
            case self::F_OPTIONS:
                $array = array();
                /**
                 * @var xlvoOption $option
                 */
                $options = $this->getXlvoVoting()->getVotingOptions();
                foreach ($options as $option) {
                   /* $array[] = array(
                        self::F_ID       => $option->getId(),
                        self::F_TEXT     => $option->getTextForEditor(),
                        self::F_POSITION => $option->getPosition(),
                        self::F_WEIGHT   => $option->getCorrectPosition(),
                    );*/
                    $array[$option->getId()] = $option->getTextForEditor();


                }

                return $array;
            default:
                throw new ilException('Unknown element can not get the value.');
                break;
        }
    }


    /**
     *
     */
    protected function handleOptions()
    {

        $ids = array();
        foreach ($this->options as $xlvoOption) {
            $xlvoOption->setVotingId($this->getXlvoVoting()->getId());
            $xlvoOption->store();
            $ids[] = $xlvoOption->getId();
        }
        $options = $this->getXlvoVoting()->getVotingOptions();



        foreach ($options as $xlvoOption) {
            if (!in_array($xlvoOption->getId(), $ids)) {
                $xlvoOption->delete();
            }
        }
        $this->getXlvoVoting()->setMultiFreeInput(true);
        //$this->getXlvoVoting()->regenerateOptionSorting();
        $this->getXlvoVoting()->store();
    }
}
