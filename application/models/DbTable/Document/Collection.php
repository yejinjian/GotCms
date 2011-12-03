<?php

/**
 * Es Object
 *
 * @category       Es_Model_DbTable
 * @package        Es_Model_DbTable_Document_Collection
 * @author          RAMBAUD Pierre
 */
class Es_Model_DbTable_Document_Collection extends Es_Db_Table implements Es_Interface_Iterable
{
    protected $_name = 'documents';

    public function load($parent_id = NULL)
    {
        if(!is_null($parent_id))
        {
            $this->setData('parent_id', $parent_id);
            $this->setDocuments();
        }
    }

    private function setDocuments()
    {
        $parent_id = $this->getParentId();
        $select = $this->select();

        if(!empty($parent_id))
        {
            $select->where('parent_id = ? ', $this->getParentId());
        }
        else
        {
            $select->where('parent_id IS NULL');
        }

        $rows = $this->fetchAll($select);
        $documents = array();
        foreach($rows as $row)
        {

            $documents[] = Es_Model_DbTable_Document_Model::fromArray($row->toArray());
        }

        $this->setData('documents', $documents);
    }

    public function getSelect()
    {
        $documents = $this->getDocuments();
        if(!is_array($documents))
        {
            $documents = array();
        }

        $array = array();
        foreach($documents as $document)
        {
            $array[$document->getId()] = $document->getName();
        }

        return $array;
    }

    /* (non-PHPdoc)
    * @see include/Es/Interface/Es_Interface_Iterable#getParent()
    */
    public function getParent()
    {
        return FALSE;
    }

    /* (non-PHPdoc)
    * @see include/Es/Interface/Es_Interface_Iterable#getChildren()
    */
    public function getChildren()
    {
        return $this->getDocuments();
    }

    /* (non-PHPdoc)
    * @see include/Es/Interface/Es_Interface_Iterable#getId()
    */
    public function getId()
    {
        return FALSE;
    }

    /* (non-PHPdoc)
    * @see include/Es/Interface/Es_Interface_Iterable#getIcon()
    */
    public function getIcon()
    {
        return 'folder';
    }

    /* (non-PHPdoc)
    * @see include/Es/Interface/Es_Interface_Iterable#getIterableId()
    */
    public function getIterableId()
    {
        return 'documents';
    }

    /* (non-PHPdoc)
    * @see include/Es/Interface/Es_Interface_Iterable#getName()
    */
    public function getName()
    {
        return 'Website';
    }

    /* (non-PHPdoc)
    * @see include/Es/Interface/Es_Interface_Iterable#getUrl()
    */
    public function getUrl()
    {
        return NULL;
    }

}
