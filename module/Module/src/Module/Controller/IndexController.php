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
 * @category Controller
 * @package  Module\Controller
 * @author   Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license  GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link     http://www.got-cms.com
 */

namespace Module\Controller;

use Gc\Component,
    Gc\Mvc\Controller\Action,
    Gc\Module\Collection as ModuleCollection,
    Gc\Module\Model as ModuleModel,
    Module\Form\Module as ModuleForm,
    Modules,
    Zend\Json\Json;
use Zend\View\Model\ViewModel;

class IndexController extends Action
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $collection = new ModuleCollection();

        return array('modules' => $collection->getChildren());
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function installAction()
    {
        $form = new ModuleForm();
        if($this->getRequest()->isPost())
        {
            $form->setData($this->getRequest()->getPost()->toArray());
            if($form->isValid())
            {
                $module_name = $form->getInputFilter()->get('module')->getValue();
                $class_name = sprintf('\\Modules\\%s\\%s', $module_name, $module_name);
                $object = new $class_name();

                if($object->install())
                {
                    $module_model = new ModuleModel();
                    $module_model->setName($module_name);
                    $module_model->save();

                    return $this->redirect()->toRoute('moduleEdit', array('m' => $module_name));
                }
            }

        }

        return array('form' => $form);
    }

    public function editAction()
    {
        $module_id = $this->getRouteMatch()->getParam('m');
        $controller_name = $this->getRouteMatch()->getParam('mc', 'index');
        $action_name = $this->getRouteMatch()->getParam('ma', 'index');

        $module_model = ModuleModel::fromId($module_id);

        $class_name = sprintf('\\Modules\\%s\\%s', $module_model->getName(), $module_model->getName());
        $object = new $class_name();
        $object->onBootstrap($this->getEvent());

        $controller_file = sprintf('\\Modules\\%s\\Controller\\%s', $module_model->getName(), ucfirst($controller_name).'Controller');
        $action = $this->getMethodFromAction($action_name);

        $controller_class = new $controller_file($this->getRequest(), $this->getResponse());

        $result = $controller_class->$action();

        if(!empty($result) and is_array($result))
        {
            $model = new ViewModel();
            $result = $model->setVariables($result);
        }
        elseif(empty($result))
        {
            $result = new ViewModel();
        }

        $result->setTemplate(sprintf('%s/views/%s/%s', $module_model->getName(), $controller_name, $action_name));

        return $result;
    }
}