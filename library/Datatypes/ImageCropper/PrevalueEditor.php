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
 * @category   Gc_Library
 * @package    Datatypes
 * @subpackage ImageCropper
 * @author     Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license    GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link       http://www.got-cms.com
 */

namespace Datatypes\ImageCropper;

use Gc\Datatype\AbstractDatatype\AbstractPrevalueEditor;
use Zend\Form\Element;

/**
 * Prevalue Editor for Image cropper datatype
 *
 * @category   Gc_Library
 * @package    Datatypes
 * @subpackage ImageCropper
 */
class PrevalueEditor extends AbstractPrevalueEditor
{
    /**
     * Save Image cropper prevalue editor
     *
     * @return void
     */
    public function save()
    {
        $post         = $this->getRequest()->getPost();
        $mimeList     = $post->get('mime_list');
        $sizesData    = $post->get('size');
        $resizeOption = $post->get('resize_option');
        $background   = $post->get('background');
        $sizes        = array();
        if (!empty($sizesData) and is_array($sizesData)) {
            foreach ($sizesData as $idx => $size) {
                if (empty($size['name']) or empty($size['height']) or empty($size['width'])) {
                    continue;
                }

                $size['name'] = str_replace(' ', '', $size['name']); // delete useless space
                $sizes[]      = $size;
            }
        }

        $this->setConfig(
            array(
                'background' => $background,
                'resize_option' => $resizeOption,
                'mime_list' => empty($mimeList) ? array() : $mimeList,
                'size' => $sizes
            )
        );
    }

    /**
     * Load Image cropper prevalue editor
     *
     * @return mixed
     */
    public function load()
    {
        $config = $this->getConfig();

        $resizeOption = new Element\Select('resize_option');
        $resizeOption->setValue(empty($config['resize_option']) ? 'auto' : $config['resize_option']);
        $resizeOption->setAttribute('class', 'input-select');
        $resizeOption->setAttribute('id', 'resize-option');
        $resizeOption->setLabel('Resize option');
        $resizeOption->setValueOptions(
            array(
                'auto' => 'auto',
                'crop' => 'crop',
            )
        );

        $backgroundOption = new Element\Text('background');
        $backgroundOption->setValue(empty($config['background']) ? '' : $config['background']);
        $backgroundOption->setAttribute('id', 'background');
        $backgroundOption->setLabel('Background color');

        $mimeList = new Element\MultiCheckbox('mime_list');
        $mimeList->setAttribute('class', 'input-checkbox');
        $array = array(
            'image/gif',
            'image/jpeg',
            'image/png',
        );

        $options = array();
        foreach ($array as $mime) {
            $options[] = array(
                'value' => $mime,
                'label' => $mime,
                'selected' =>
                    !in_array(
                        $mime,
                        empty($config['mime_list']) ? array() : $config['mime_list']
                    ) ? false : true,
            );
        }

        $mimeList->setValueOptions($options);
        $sizeElements = array();
        $idx          = 0;
        if (!empty($config['size'])) {
            foreach ($config['size'] as $idx => $size) {
                $elementSizeName = new Element\Text('size[' . $idx . '][name]');
                $elementSizeName->setValue($size['name']);
                $elementSizeName->setAttribute('id', 'name' . $idx);
                $elementSizeName->setLabel('Name');

                $elementWidth = new Element\Text('size[' . $idx . '][width]');
                $elementWidth->setValue($size['width']);
                $elementWidth->setAttribute('id', 'width' . $idx);
                $elementWidth->setLabel('Width');

                $elementHeight = new Element\Text('size[' . $idx . '][height]');
                $elementHeight->setValue($size['height']);
                $elementHeight->setAttribute('id', 'height' . $idx);
                $elementHeight->setLabel('Height');
                $sizeElements[] = array($elementSizeName, $elementWidth, $elementHeight);
            }

            $idx++;
        }

        $elementSizeName = new Element\Text('size[#{idx}][name]');
        $elementSizeName->setAttribute('id', 'name#{idx}');
        $elementSizeName->setLabel('Name');

        $elementWidth = new Element\Text('size[#{idx}][width]');
        $elementWidth->setLabel('Width');
        $elementWidth->setAttribute('id', 'width#{idx}');
        $elementHeight = new Element\Text('size[#{idx}][height]');
        $elementHeight->setLabel('Height');
        $elementHeight->setAttribute('id', 'height#{idx}');
        $template = array($elementSizeName, $elementWidth, $elementHeight);

        return $this->addPath(__DIR__)->render(
            'upload-prevalue.phtml',
            array(
                'elements' => array(
                    'resize-option' => $resizeOption,
                    'background' => $backgroundOption,
                    'mime' => $mimeList,
                    'size' => $sizeElements,
                    'size-template' => $template
                )
            )
        );
    }
}
