<?php
/**
 * This source file is part of GotCms.
 *
 * GotCms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GotCms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with GotCms. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category Gc_Tests
 * @package  Datatypes
 * @author   Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license  GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link     http://www.got-cms.com
 */

namespace Datatypes\Boolean;

use Gc\Datatype\Model as DatatypeModel;
use Gc\DocumentType\Model as DocumentTypeModel;
use Gc\Layout\Model as LayoutModel;
use Gc\Property\Model as PropertyModel;
use Gc\User\Model as UserModel;
use Gc\Tab\Model as TabModel;
use Gc\View\Model as ViewModel;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:42:12.
 *
 * @group Datatypes
 * @category Gc_Tests
 * @package  Datatypes
 */
class DatatypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Datatype
     *
     * @return void
     */
    protected $object;

    /**
     * @var DatatypeModel
     *
     * @return void
     */
    protected $datatype;

    /**
     * @var PropertyModel
     *
     * @return void
     */
    protected $property;

    /**
     * @var ViewModel
     *
     * @return void
     */
    protected $view;

    /**
     * @var LayoutModel
     *
     * @return void
     */
    protected $layout;

    /**
     * @var TabModel
     *
     * @return void
     */
    protected $tab;

    /**
     * @var UserModel
     *
     * @return void
     */
    protected $user;

    /**
     * @var DocumentTypeModel
     *
     * @return void
     */
     protected $documentType;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->view = ViewModel::fromArray(
            array(
                'name' => 'View Name',
                'identifier' => 'View identifier',
                'description' => 'View Description',
                'content' => 'View Content'
            )
        );
        $this->view->save();

        $this->layout = LayoutModel::fromArray(
            array(
                'name' => 'Layout Name',
                'identifier' => 'Layout identifier',
                'description' => 'Layout Description',
                'content' => 'Layout Content'
            )
        );
        $this->layout->save();

        $this->user = UserModel::fromArray(
            array(
                'lastname' => 'User test',
                'firstname' => 'User test',
                'email' => 'pierre.rambaud86@gmail.com',
                'login' => 'test',
                'user_acl_role_id' => 1,
            )
        );
        $this->user->setPassword('test');
        $this->user->save();

        $this->documentType = DocumentTypeModel::fromArray(
            array(
                'name' => 'Document Type Name',
                'description' => 'Document Type description',
                'icon_id' => 1,
                'defaultview_id' => $this->view->getId(),
                'user_id' => $this->user->getId(),
            )
        );
        $this->documentType->save();

        $this->datatype = DatatypeModel::fromArray(
            array(
                'name' => 'BooleanTest',
                'prevalue_value' => '',
                'model' => 'Boolean',
            )
        );
        $this->datatype->save();

        $this->tab = TabModel::fromArray(
            array(
                'name' => 'TabTest',
                'description' => 'TabTest',
                'sort_order' => 1,
                'document_type_id' => $this->documentType->getId(),
            )
        );
        $this->tab->save();

        $this->property = PropertyModel::fromArray(
            array(
                'name' => 'DatatypeTest',
                'identifier' => 'DatatypeTest',
                'description' => 'DatatypeTest',
                'required' => false,
                'sort_order' => 1,
                'tab_id' => $this->tab->getId(),
                'datatype_id' => $this->datatype->getId(),
            )
        );
        $this->property->save();
        $this->object = new Datatype($this->datatype);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->datatype->delete();
        $this->documentType->delete();
        $this->layout->delete();
        $this->property->delete();
        $this->tab->delete();
        $this->user->delete();
        $this->view->delete();

        unset($this->datatype);
        unset($this->documentType);
        unset($this->layout);
        unset($this->property);
        unset($this->tab);
        unset($this->user);
        unset($this->view);
        unset($this->object);
    }

    /**
     * Test
     *
     * @covers Datatypes\Boolean\Datatype
     *
     * @return void
     */
    public function testGetEditor()
    {
        $this->assertInstanceOf('Datatypes\Boolean\Editor', $this->object->getEditor($this->property));
    }

    /**
     * Test
     *
     * @covers Datatypes\Boolean\Datatype
     *
     * @return void
     */
    public function testGetPrevalueEditor()
    {
        $this->assertInstanceOf('Datatypes\Boolean\PrevalueEditor', $this->object->getPrevalueEditor());
    }
}
