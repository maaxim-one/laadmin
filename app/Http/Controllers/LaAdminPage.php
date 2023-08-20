<?php

namespace MaaximOne\LaAdmin\Http\Controllers;

use MaaximOne\LaAdmin\Facades\LaAdminPage as LaAdminPageFacade;
use MaaximOne\LaAdmin\Exceptions\LaAdminException;
use MaaximOne\LaAdmin\Classes\Page\FileField;
use MaaximOne\LaAdmin\Classes\Page\Field;
use MaaximOne\LaAdmin\Classes\Page\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LaAdminPage extends AdminController
{
    protected Request $_request;

    public function __construct(Request $request)
    {
        $this->_request = $this->replaceRequest($request);
    }

    public function getPages()
    {
        return response()->json(
            LaAdminPageFacade::__toResponse()
        );
    }

    /**
     * @throws LaAdminException
     */
    public function getData($pageName = null, $id = null)
    {
        if ($pageName == null) $pageName = $this->_request->input('pageName');
        if ($id == null) $id = $this->_request->input('id');

        $page = LaAdminPageFacade::getPage($pageName);

        if ($id != null) {
            $data = $page->_page->model::findOrFail($id);
        } else {
            return response()->json(
                $page->_page->model::all()
            );
        }

        if (Arr::has($page->getFields(), 'files')) {
            /**
             * @var $file FileField
             */
            $files = [];

            foreach ($page->getFields()['files'] as $file) {
                foreach ($data->{$file->_name} as $item) {
                    if ($file->_public_path == null) {
                        throw new LaAdminException(
                            "_public_path для поля {$file->_name} страницы {$page->_page->title} не указан",
                            500
                        );
                    }

                    $files[] = [
                        'size' => $this->fileSize($file->_path . '/' . $item),
                        'type' => File::extension($file->_path . '/' . $item),
                        'url' => asset($file->_public_path . '/' . $item),
                        'name' => $item,
                    ];
                }

                $data->{$file->_name} = null;
            }

            $data->files = $files;
        }

        return response()->json($data);
    }

    public function getPage($pageName = null)
    {
        if ($pageName == null) $pageName = $this->_request->input('pageName');

        return response()->json(
            LaAdminPageFacade::getPage($pageName)->__toResponse()
        );
    }

    protected function fileSize($filePath)
    {
        $size = File::size($filePath);

        if ($size >= 1073741824) {
            $size = number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            $size = number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            $size = number_format($size / 1024, 2) . ' KB';
        } elseif ($size > 1) {
            $size = $size . ' bytes';
        } elseif ($size == 1) {
            $size = $size . ' byte';
        } else {
            $size = '0 bytes';
        }

        return $size;
    }

    public function saveData($pageName = null, $id = null)
    {
        /**
         * @var Field $field
         * @var Model $data
         */

        if ($pageName == null) $pageName = $this->_request->input('pageName');
        if ($id == null) $id = $this->_request->input('id');
        $page = LaAdminPageFacade::getPage($pageName);
        $data = $this->validatePost($page, $page->_page->model::findOrFail($id), 'edit');

        if (Arr::has($page->getFields(), 'files')) {
            foreach ($page->getFields()['files'] as $file) {
                $data->{$file->_name} = array_merge(
                    collect($data->{$file->_name})->toArray(),
                    $this->uploadFiles($file)
                );
            }
        }

        $data->save();

        return response()->json(true);
    }

    protected function validatePost(Page $page, Model $data, $method = null): Model
    {
        /**
         * @var $field Field
         */

        $rules = [];

        foreach ($page->getFields() as $key => $field) {
            if ($key != 'files') {
                $data->{$field->_name} = $this->_request->input($field->_name);

                if ($method == 'add' && $field->_validationRulesAdd != null) {
                    $rules[$field->_name] = $field->_validationRulesAdd;
                } elseif ($method == 'edit' && $field->_validationRulesEdit != null) {
                    $rules[$field->_name] = $field->_validationRulesEdit;
                } elseif ($field->_validationRules != null) {
                    $rules[$field->_name] = $field->_validationRules;
                }
            }
        }

        if (Arr::has($page->getFields(), 'files')) {
            foreach ($page->getFields()['files'] as $file) {
                if ($file->_validationRules != null) {
                    $rules[$file->_name] = $file->_validationRules;
                }
            }
        }

        $this->_request->validate($rules);

        return $data;
    }

    protected function uploadFiles($field)
    {
        /**
         * @var $field FileField
         */

        $files = [];

        if ($this->_request->hasFile($field->_name)) {
            foreach ($this->_request->file($field->_name) as $file) {
                $name = $file->hashName();
                $file->move($field->_path, $name);
                $files[] = $name;
            }
        }

        return $files;
    }

    public function addPost($pageName = null)
    {
        /**
         * @var Field $field
         * @var Model $data
         */

        if ($pageName == null) $pageName = $this->_request->input('pageName');
        $page = LaAdminPageFacade::getPage($pageName);
        $data = $this->validatePost($page, (new $page->_page->model()), 'add');

        if (Arr::has($page->getFields(), 'files')) {
            foreach ($page->getFields()['files'] as $file) {
                $data->{$file->_name} = $this->uploadFiles($file);
            }
        }

        $data->save();

        return response()->json(true);
    }

    public function downloadFile()
    {
        /**
         * @var $field FileField
         */

        $field = LaAdminPageFacade::getPage($this->_request->input('pageName'))
            ->getFields()['files'][$this->_request->input('field')];

        return response()->json(
            '/' . $field->_public_path . '/' . $this->_request->input('file')
        );
    }

    public function deleteFile()
    {
        /**
         * @var $field FileField
         * @var $post Model
         */

        $page = LaAdminPageFacade::getPage($this->_request->input('pageName'));
        $field = $page->getFields()['files'][$this->_request->input('field')];
        $file = $field->_path . '/' . $this->_request->input('file');

        if (File::exists($file)) {
            File::delete($file);
        }

        $post = $page->_page->model::findOrFail($this->_request->input('id'));
        $files = $post->{$field->_name};
        foreach ($files as $key => $item) {
            if ($item === $this->_request->input('file')) {
                Arr::forget($files, $key);
                break;
            }
        }

        $post->{$field->_name} = $files;
        $post->save();

        return response()->json(true);
    }

    public function deletePost($pageName = null, $id = null)
    {
        /**
         * @var $post Model
         * @var $field FileField
         */

        if ($pageName == null) $pageName = $this->_request->input('pageName');
        if ($id == null) $id = $this->_request->input('id');
        $page = LaAdminPageFacade::getPage($pageName);
        $post = $page->_page->model::findOrFail($id);

        if (Arr::has($page->getFields(), 'files')) {
            foreach ($page->getFields()['files'] as $field) {
                foreach ($post->{$field->_name} as $file) {
                    $file = $field->_path . '/' . $file;

                    if (File::exists($file)) {
                        File::delete($file);
                    }
                }
            }
        }

        return response()->json($post->delete());
    }
}
