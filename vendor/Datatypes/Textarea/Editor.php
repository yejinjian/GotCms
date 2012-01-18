<?php
class Datatypes_Textarea_Editor extends Es_Model_DbTable_Datatype_Abstract_Editor
{
    public function save()
    {
        $value = $this->getRequest()->getParam($this->getName());
        $this->setValue($value);
    }

    public function load()
    {
        $textarea = new Zend_Form_Element_Textarea($this->getName());
        $textarea->setLabel($this->getProperty()->getName());
        $textarea->setValue($this->getProperty()->getValue());

        return $textarea;
    }
}
