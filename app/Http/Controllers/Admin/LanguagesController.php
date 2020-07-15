<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index()
    {

        $languages = Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {

        return view('admin.languages.create');

    }

    public function store(LanguageRequest $request)
    {

        try {
            Language::create($request->except(['_token']));
            return redirect()->route('admin.languages')->with('success', 'تم حفظ البيانات بنجاح');


        } catch (\Exception $exception) {

            return redirect()->route('admin.languages')->with('error', 'هناك خطأ حاول فيما بعد');

        }

    }

    public function edit($id)
    {

        $language = Language::find($id);
        if (!$language) {
            return redirect()->route('admin.languages')->with('error', 'هذه اللغه غير موجوده');
        }
        return view('admin.languages.edit', compact('language'));
    }

    public function update($id, LanguageRequest $request)
    {

        try {
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages.edit', $id)->with('error', 'هذه اللغه غير موجوده');

            }

            if (!$request->has('active')) {
                $request->request->add(['active' => 0]);

            }

            $language->update($request->except(['_token', '_method']));

            return redirect()->route('admin.languages')->with('success', 'تم تعديل البيانات بنجاح');

        } catch (\Exception $exception) {
            return redirect()->route('admin.languages')->with('error', 'هناك خطأ حاول فيما بعد');

        }


    }

    public function destroy($id)
    {

        try {

            $language = Language::find($id);
            if (!$language) {

                return redirect()->route('admin.languages.edit', $id)->with('error', 'هذه اللغه غير موجوده');
            }

            $language->delete();

            return redirect()->route('admin.languages')->with('success', 'تم حذف البيانات بنجاح');

        } catch (\Exception $exception) {

            return redirect()->route('admin.languages')->with('error', 'هناك خطأ حاول فيما بعد');

        }

    }
}
